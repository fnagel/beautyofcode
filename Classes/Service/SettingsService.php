<?php

namespace FelixNagel\Beautyofcode\Service;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Provide a way to get the configuration just everywhere.
 *
 * @author (c) 2010 Sebastian Schreiber <me@schreibersebastian.de >
 * @author (c) 2010 Georg Ringer <typo3@ringerge.org>
 * @author (c) 2013-2024 Felix Nagel <info@felixnagel.com>
 */
class SettingsService
{
    /**
     * Extension name.
     *
     * Needed as parameter for configurationManager->getConfiguration when used
     * in BE context otherwise generated TS will be incorrect or missing
     */
    protected string $extensionName = 'beautyofcode';

    /**
     * Extension key.
     */
    protected string $extensionKey = 'tx_beautyofcode';

    protected ?array $typoScriptSettings = null;

    /**
     * Page uid for TS generation in BE context
     */
    protected int $pid = 0;

    /**
     * SettingsService constructor.
     */
    public function __construct(int $pid = 0)
    {
        $this->pid = $pid;
    }

    /**
     * Returns all TS settings.
     */
    public function getTypoScriptSettings(): array
    {
        if ($this->typoScriptSettings === null) {
            if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
                $configuration = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
                $this->typoScriptSettings = $configuration->getConfiguration(
                    ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                    $this->extensionName,
                    $this->extensionKey
                );
            } else {
                $setup = $this->generateTypoScript($this->pid, $GLOBALS['TYPO3_REQUEST']);

                if (!empty($setup['plugin.'][$this->extensionKey.'.']['settings.'])) {
                    $this->typoScriptSettings = GeneralUtility::makeInstance(TypoScriptService::class)
                        ->convertTypoScriptArrayToPlainArray($setup['plugin.'][$this->extensionKey.'.']['settings.']);
                }
            }
        }

        if ($this->typoScriptSettings === null) {
            throw new Exception('No TypoScript settings for EXT:'.$this->extensionName.' available.');
        }

        return $this->typoScriptSettings;
    }

    /**
     * Returns the settings at path $path, which is separated by ".",
     * e.g. "pages.uid".
     * "pages.uid" would return $this->settings['pages']['uid'].
     *
     * If the path is invalid or no entry is found, false is returned.
     *
     * @return mixed
     */
    public function getTypoScriptByPath(string $path)
    {
        return ObjectAccess::getPropertyPath($this->getTypoScriptSettings(), $path);
    }

    /**
     * Returns all global settings.
     *
     * Taken from \TYPO3\CMS\Redirects\Service\RedirectService::bootFrontendController
     */
    protected function generateTypoScript(int $pid, ServerRequestInterface $request): array
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pid);

        $controller = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            GeneralUtility::makeInstance(Context::class),
            $site,
            $site->getDefaultLanguage(),
            new PageArguments($site->getRootPageId(), '0', []),
            GeneralUtility::makeInstance(FrontendUserAuthentication::class)
        );

        // @extensionScannerIgnoreLine
        $controller->id = $pid;
        $controller->determineId($request);

        return $controller->getFromCache($request)->getAttribute('frontend.typoscript')->getSetupArray();
    }
}
