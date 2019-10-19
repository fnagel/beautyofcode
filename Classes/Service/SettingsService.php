<?php

namespace FelixNagel\Beautyofcode\Service;

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

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Provide a way to get the configuration just everywhere.
 *
 * @author (c) 2010 Sebastian Schreiber <me@schreibersebastian.de >
 * @author (c) 2010 Georg Ringer <typo3@ringerge.org>
 * @author (c) 2013-2018 Felix Nagel <info@felixnagel.com>
 */
class SettingsService
{
    /**
     * Extension name.
     *
     * Needed as parameter for configurationManager->getConfiguration when used
     * in BE context otherwise generated TS will be incorrect or missing
     *
     * @var string
     */
    protected $extensionName = 'beautyofcode';

    /**
     * Extension key.
     *
     * @var string
     */
    protected $extensionKey = 'tx_beautyofcode';


    /**
     * @var mixed
     */
    protected $typoScriptSettings = null;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Legacy alias of \TYPO3\CMS\Extbase\Service\TypoScriptService
     *
     * @var \TYPO3\CMS\Core\TypoScript\TypoScriptService
     */
    protected $typoScriptService;

    /**
     * Page uid for TS generation in BE context
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * SettingsService constructor.
     *
     * @param int $pid
     */
    public function __construct(int $pid)
    {
        $this->pid = $pid;
    }

    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    public function injectTypoScriptService(\TYPO3\CMS\Core\TypoScript\TypoScriptService $typoScriptService)
    {
        $this->typoScriptService = $typoScriptService;
    }

    /**
     * Returns all TS settings.
     *
     * @return array
     *
     * @throws Exception
     */
    public function getTypoScriptSettings()
    {
        if ($this->typoScriptSettings === null) {
            if (TYPO3_MODE === 'FE') {
                $this->typoScriptSettings = $this->configurationManager->getConfiguration(
                    ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                    $this->extensionName,
                    $this->extensionKey
                );
            } else {
                $this->typoScriptSettings = $this->generateTypoScript($this->pid)['settings'];
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
     * @param string $path
     *
     * @return mixed
     */
    public function getTypoScriptByPath($path)
    {
        return ObjectAccess::getPropertyPath($this->getTypoScriptSettings(), $path);
    }

    /**
     * Returns all TS settings.
     *
     * @param int $pid
     *
     * @return array
     */
    protected function generateTypoScript($pid)
    {
        /* @var $rootLineUtility RootlineUtility */
        $rootLineUtility = GeneralUtility::makeInstance(RootlineUtility::class, $pid);
        $rootLine = $rootLineUtility->get();

        /* @var $templateService TemplateService */
        $templateService = GeneralUtility::makeInstance(TemplateService::class);
        $templateService->tt_track = false;
        $templateService->runThroughTemplates($rootLine);
        $templateService->generateConfig();

        if (!empty($templateService->setup['plugin.'][$this->extensionKey.'.'])) {
            return $this->typoScriptService->convertTypoScriptArrayToPlainArray(
                $templateService->setup['plugin.'][$this->extensionKey.'.']
            );
        }

        return null;
    }
}
