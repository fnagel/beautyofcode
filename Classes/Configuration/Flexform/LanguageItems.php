<?php

namespace FelixNagel\Beautyofcode\Configuration\Flexform;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use FelixNagel\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use FelixNagel\Beautyofcode\Service\SettingsService;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Function to add select options dynamically (loaded in flexform).
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class LanguageItems
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * ConfigurationInterface.
     *
     * @var ConfigurationInterface
     */
    protected $highlighterConfiguration;

    /**
     * Page uid (PID) for TypoScript generation.
     *
     * Fallback to root PID (0)
     *
     * @var int
     */
    protected $contentElementPid = 0;

    /**
     * InjectCacheManager.
     */
    public function injectCacheManager(CacheManager $cacheManager = null)
    {
        if (is_null($cacheManager)) {
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        }

        $this->cacheManager = $cacheManager;
    }

    /**
     * Injects the highlighter configuration.
     */
    public function injectHighlighterConfiguration(ConfigurationInterface $configuration = null)
    {
        if (is_null($configuration)) {
            $configuration = GeneralUtility::makeInstance(
                ConfigurationInterface::class,
                $this->contentElementPid
            );
        }

        $this->highlighterConfiguration = $configuration;
    }

    /**
     * This function is called from the flexform and
     * adds available programming languages to the select options.
     *
     * @SuppressWarnings("CyclomaticComplexity")
     *
     * @param array $config flexform data
     *
     * @return array
     */
    public function getConfiguredLanguages($config)
    {
        $this->injectCacheManager($this->cacheManager);

        if (($cachedFields = $this->getCache()->get('language-items')) !== false) {
            $config['items'] = $cachedFields;
        } else {
            // make brushes list to flexform selectbox item array
            $optionList = [];

            if (isset($config['flexParentDatabaseRow']['pid']) && is_numeric($config['flexParentDatabaseRow']['pid'])) {
                $this->contentElementPid = (int) $config['flexParentDatabaseRow']['pid'];
            }

            if ($this->contentElementPid === 0 && isset($config['row']['uid']) && is_numeric($config['row']['uid'])) {
                $this->contentElementPid = $this->getPageUidByRecordUid($config['row']['uid']);
            }

            // Inject configuration after determining the PID
            $this->injectHighlighterConfiguration($this->highlighterConfiguration);

            $brushesArray = $this->getUniqueAndSortedBrushes();

            foreach ($brushesArray as $i => $brush) {
                if (strtolower($brush) === 'plain') {
                    continue;
                }

                // skip unknown brushes
                if (!$this->highlighterConfiguration->hasBrushIdentifier($brush)) {
                    continue;
                }

                $optionList[$i] = array_reverse(
                    $this->highlighterConfiguration->getBrushIdentifierAliasAndLabel($brush)
                );
            }

            $config['items'] = array_merge($config['items'], $optionList);
        }

        $this->getCache()->set('language-items', $config['items'], ['beautyofcode']);

        return $config;
    }

    /**
     * Returns the page uid by given record uid.
     *
     * @param int $recordUid Record uid
     */
    private function getPageUidByRecordUid($recordUid): int
    {
        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $recordPid = $queryBuilder
            ->select('pid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                'uid',
                    $queryBuilder->createNamedParameter($recordUid, \PDO::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        return (int) $recordPid['pid'];
    }

    /**
     * Returns unique and sorted brushes.
     *
     * @return array
     */
    protected function getUniqueAndSortedBrushes()
    {
        $brushesArray = GeneralUtility::trimExplode(',', $this->getBrushesConfig(), true);

        // make unique
        foreach ($brushesArray as &$value) {
            $value = serialize($value);
        }

        $brushesArray = array_unique($brushesArray);

        foreach ($brushesArray as &$value) {
            $value = unserialize($value);
        }

        // sort a-z
        sort($brushesArray);

        return $brushesArray;
    }

    /**
     * Get brushes TS config per page.
     *
     * @return string
     */
    protected function getBrushesConfig()
    {
        return $this->getSettingsService($this->contentElementPid)->getTypoScriptByPath('brushes');
    }

    /**
     * Get the settings service.
     *
     * @param int $pid PID of the page
     *
     * @return SettingsService
     */
    public function getSettingsService($pid = 0)
    {
        return GeneralUtility::makeInstance(SettingsService::class, $pid);
    }

    /**
     * Get the constants cache.
     *
     * @return FrontendInterface
     */
    protected function getCache()
    {
        return $this->cacheManager->getCache('cache_beautyofcode');
    }

    protected function getQueryBuilderForTable(string $table): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }
}
