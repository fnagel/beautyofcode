<?php

namespace TYPO3\Beautyofcode\Highlighter\Configuration;

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

/**
 * AbstractConfiguration.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
abstract class AbstractConfiguration implements \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
{
    /**
     * Settings aray.
     *
     * @var array
     */
    protected $settings;

    /**
     * Failsafe brush alias map.
     *
     * Fallback from one highlighter engine to another.
     * Key Prism SH CSS class and value is SH CSS class.
     *
     * @var array
     */
    protected $failSafeBrushAliasMap = [];

    /**
     * A CSS class/label map for the select box.
     *
     * Key is the brush string from TS Setup; Value is an array with the CSS
     * class in key 0 and the label for the select box in key 1
     *
     * @var array
     */
    protected $brushIdentifierAliasLabelMap = [];

    /**
     * Constructor.
     *
     * @param array $settings Settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
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

    /**
     * HasBrushIdentifier.
     *
     * @param string $brushIdentifier Brush identifier
     *
     * @return bool
     */
    public function hasBrushIdentifier($brushIdentifier)
    {
        return isset($this->brushIdentifierAliasLabelMap[$brushIdentifier]);
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
        foreach ($this->brushIdentifierAliasLabelMap as $aliasLabelMap) {
            list($alias) = $aliasLabelMap;
            if ($alias === $brushAlias) {
                return true;
            }
        }

        return false;
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
        return $this->brushIdentifierAliasLabelMap[$brushIdentifier];
    }
}
