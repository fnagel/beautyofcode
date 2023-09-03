<?php

namespace FelixNagel\Beautyofcode\Highlighter;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Service\SettingsService;
use FelixNagel\Beautyofcode\Domain\Model\Flexform;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Configuration.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Settings array.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * ConfigurationInterface.
     *
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * Page uid
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * @param int $pid
     */
    public function __construct($pid = 0)
    {
        $this->pid = (int)$pid;

        $this->initializeObject();
    }

    /**
     * InitializeObject.
     */
    public function initializeObject()
    {
        $settingsService = GeneralUtility::makeInstance(
            SettingsService::class,
            $this->pid
        );
        $this->settings = $settingsService->getTypoScriptSettings();

        $this->configuration = GeneralUtility::makeInstance(
            'FelixNagel\\Beautyofcode\\Highlighter\\Configuration\\'.$this->settings['library'],
            $this->settings
        );
    }

    /**
     * GetFailSafeBrushAlias.
     *
     * @param string $brushAlias Brush alias
     *
     * @return string
     */
    public function getFailSafeBrushAlias($brushAlias)
    {
        return $this->configuration->getFailSafeBrushAlias($brushAlias);
    }

    /**
     * HasBrushIdentifier.
     *
     * @param string $brushIdentifier Brush identifier
     *
     * @return bool
     */
    public function hasBrushIdentifier($brushIdentifier)
    {
        return $this->configuration->hasBrushIdentifier($brushIdentifier);
    }

    /**
     * HasBrushAlias.
     *
     * @param string $brushAlias Brush alias
     *
     * @return bool
     */
    public function hasBrushAlias($brushAlias)
    {
        return $this->configuration->hasBrushAlias($brushAlias);
    }

    /**
     * GetBrushIdentifierAliasAndLabel.
     *
     * @param string $brushIdentifier Brush identifier
     *
     * @return array
     */
    public function getBrushIdentifierAliasAndLabel($brushIdentifier)
    {
        return $this->configuration->getBrushIdentifierAliasAndLabel($brushIdentifier);
    }

    /**
     * GetAutoloaderBrushMap.
     *
     * @return array
     */
    public function getAutoloaderBrushMap()
    {
        return $this->configuration->getAutoloaderBrushMap();
    }

    /**
     * GetClassAttributeString.
     *
     * @param Flexform $flexform Flexform
     *
     * @return string
     */
    public function getClassAttributeString(Flexform $flexform)
    {
        return $this->configuration->getClassAttributeString($flexform);
    }
}
