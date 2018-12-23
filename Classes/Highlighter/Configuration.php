<?php

namespace TYPO3\Beautyofcode\Highlighter;

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

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

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
    protected $settings;

    /**
     * ConfigurationInterface.
     *
     * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
     */
    protected $configuration;

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
     * InjectConfiguration.
     *
     * @param ConfigurationManagerInterface $configurationManager ConfigurationManagerInterface
     */
    public function injectConfiguration(ConfigurationManagerInterface $configurationManager)
    {
        $configuration = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $this->settings = ArrayUtility::getValueByPath($configuration, 'plugin./tx_beautyofcode./settings.');
    }

    /**
     * InitializeObject.
     */
    public function initializeObject()
    {
        $this->configuration = $this->objectManager->get(
            'TYPO3\\Beautyofcode\\Highlighter\\Configuration\\' . $this->settings['library'],
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
     * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform Flexform
     *
     * @return string
     */
    public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform)
    {
        return $this->configuration->getClassAttributeString($flexform);
    }
}
