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
    protected array $settings = [];

    protected ConfigurationInterface $configuration;

    /**
     * Page uid
     */
    protected int $pid = 0;

    public function __construct(int $pid = 0)
    {
        $this->pid = $pid;

        $this->initializeObject();
    }

    /**
     * InitializeObject.
     */
    public function initializeObject()
    {
        $settingsService = SettingsService::create($this->pid);
        $this->settings = $settingsService->getTypoScriptSettings();

        $this->configuration = GeneralUtility::makeInstance(
            'FelixNagel\\Beautyofcode\\Highlighter\\Configuration\\'.$this->settings['library'],
            $this->settings
        );
    }

    public function getFailSafeBrushAlias(string $brushAlias): string
    {
        return $this->configuration->getFailSafeBrushAlias($brushAlias);
    }

    public function hasBrushIdentifier(string $brushIdentifier): bool
    {
        return $this->configuration->hasBrushIdentifier($brushIdentifier);
    }

    public function hasBrushAlias(string $brushAlias): bool
    {
        return $this->configuration->hasBrushAlias($brushAlias);
    }

    public function getBrushIdentifierAliasAndLabel(string $brushIdentifier): array
    {
        return $this->configuration->getBrushIdentifierAliasAndLabel($brushIdentifier);
    }

    public function getAutoloaderBrushMap(): array
    {
        return $this->configuration->getAutoloaderBrushMap();
    }

    public function getClassAttributeString(Flexform $flexform): string
    {
        return $this->configuration->getClassAttributeString($flexform);
    }
}
