<?php

namespace FelixNagel\Beautyofcode\Highlighter;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use FelixNagel\Beautyofcode\Service\SettingsService;
use FelixNagel\Beautyofcode\Domain\Model\Flexform;

/**
 * Configuration.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * ObjectManager.
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Settings array.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * ConfigurationInterface.
     *
     * @var \FelixNagel\Beautyofcode\Highlighter\ConfigurationInterface
     */
    protected $configuration;

    /**
     * Page uid
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * InjectObjectManager.
     *
     * @param ObjectManagerInterface $objectManager ObjectManagerInterface
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param int $pid
     */
    public function __construct($pid = 0)
    {
        $this->pid = (int)$pid;
    }

    /**
     * InitializeObject.
     */
    public function initializeObject()
    {
        $settingsService = $this->objectManager->get(
            SettingsService::class,
            $this->pid
        );
        $this->settings = $settingsService->getTypoScriptSettings();

        $this->configuration = $this->objectManager->get(
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
     * @param \FelixNagel\Beautyofcode\Domain\Model\Flexform $flexform Flexform
     *
     * @return string
     */
    public function getClassAttributeString(Flexform $flexform)
    {
        return $this->configuration->getClassAttributeString($flexform);
    }
}
