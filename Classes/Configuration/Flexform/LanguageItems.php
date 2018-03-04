<?php

namespace TYPO3\Beautyofcode\Configuration\Flexform;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\Beautyofcode\Service\SettingsService;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Function to add select options dynamically (loaded in flexform).
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class LanguageItems
{
    /**
     * ObjectManager.
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cacheManager;

    /**
     * SettingsService.
     *
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * ConfigurationInterface.
     *
     * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
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
     * InjectObjectManager.
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager ObjectManagerInterface
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager = null)
    {
        if (is_null($objectManager)) {
            $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        }

        $this->objectManager = $objectManager;
    }
    /**
     * InjectCacheManager.
     *
     * @param \TYPO3\CMS\Core\Cache\CacheManager $cacheManager
     */
    public function injectCacheManager(CacheManager $cacheManager = null)
    {
        if (is_null($cacheManager)) {
            $cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
        }

        $this->cacheManager = $cacheManager;
    }

    /**
     * Injects the highlighter configuration.
     *
     * @param ConfigurationInterface $configuration
     */
    public function injectHighlighterConfiguration(ConfigurationInterface $configuration = null)
    {
        if (is_null($configuration)) {
            $configuration = $this->objectManager->get('TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface');
        }

        $this->highlighterConfiguration = $configuration;
    }

    /**
     * Initialize.
     */
    public function initialize()
    {
        $this->injectObjectManager($this->objectManager);
        $this->injectCacheManager($this->cacheManager);
        $this->injectHighlighterConfiguration($this->highlighterConfiguration);
    }

    /**
     * This function is called from the flexform and
     * adds available programming languages to the select options.
     *
     * @param array $config flexform data
     *
     * @return array
     */
    public function getConfiguredLanguages($config)
    {
        $this->initialize();

        if (($cachedFields = $this->getCache()->get('language-items')) !== false) {
            $config['items'] = $cachedFields;
        } else {
            // make brushes list to flexform selectbox item array
            $optionList = array();

            if (isset($config['row']['pid']) && is_numeric($config['row']['pid'])) {
                $this->contentElementPid = (int) $config['row']['pid'];
            }

            if ($this->contentElementPid === 0 && isset($config['row']['uid']) && is_numeric($config['row']['uid'])) {
                $this->contentElementPid = $this->getPageUidByRecordUid($config['row']['uid']);
            }

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

        $this->getCache()->set('language-items', $config['items'], array('beautyofcode'));

        return $config;
    }

    /**
     * Returns the page uid by given record uid.
     *
     * @param int $recordUid Record uid
     *
     * @return int
     */
    private function getPageUidByRecordUid($recordUid)
    {
        $recordPid = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
            'pid', 'tt_content', 'uid = '.$recordUid
        );

        return (int) $recordPid['pid'];
    }

    /**
     * Returns the global DatabaseConnection instance.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    private function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
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
     * @return \TYPO3\Beautyofcode\Service\SettingsService
     */
    public function getSettingsService($pid = 0)
    {
        return $this->objectManager->get('TYPO3\\Beautyofcode\\Service\\SettingsService', $pid);
    }

    /**
     * Get the constants cache.
     *
     * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    protected function getCache()
    {
        return $this->cacheManager->getCache('cache_beautyofcode');
    }
}
