<?php

namespace FelixNagel\Beautyofcode\Highlighter\Configuration;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Highlighter\ConfigurationInterface;

/**
 * AbstractConfiguration.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
abstract class AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Settings array.
     */
    protected array $settings = [];

    /**
     * Failsafe brush alias map.
     *
     * Fallback from one highlighter engine to another.
     * Key Prism SH CSS class and value is SH CSS class.
     */
    protected array $failSafeBrushAliasMap = [];

    /**
     * A CSS class/label map for the select box.
     *
     * Key is the brush string from TS Setup; Value is an array with the CSS
     * class in key 0 and the label for the select box in key 1
     */
    protected array $brushIdentifierAliasLabelMap = [];

    /**
     * Constructor.
     *
     * @param array $settings Settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getFailSafeBrushAlias(string $brushAlias): string
    {
        if ($this->hasBrushAlias($brushAlias)) {
            return $brushAlias;
        }

        $failSafeBrushAlias = '';
        foreach ($this->failSafeBrushAliasMap as $foreignLibraryMap) {
            if (isset($foreignLibraryMap[$brushAlias])) {
                $failSafeBrushAlias = $foreignLibraryMap[$brushAlias];
                break;
            }
        }

        return $failSafeBrushAlias;
    }

    public function hasBrushIdentifier(string $brushIdentifier): bool
    {
        return isset($this->brushIdentifierAliasLabelMap[$brushIdentifier]);
    }

    public function hasBrushAlias(string $brushAlias): bool
    {
        foreach ($this->brushIdentifierAliasLabelMap as $aliasLabelMap) {
            [$alias] = $aliasLabelMap;
            if ($alias === $brushAlias) {
                return true;
            }
        }

        return false;
    }

    public function getBrushIdentifierAliasAndLabel(string $brushIdentifier): array
    {
        return $this->brushIdentifierAliasLabelMap[$brushIdentifier];
    }
}
