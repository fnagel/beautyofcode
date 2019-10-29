<?php

namespace FelixNagel\Beautyofcode\Highlighter;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * ConfigurationInterface.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
interface ConfigurationInterface
{
    /**
     * GetFailSafeBrushAlias.
     *
     * @param string $brushAlias Brush alias
     *
     * @return string
     */
    public function getFailSafeBrushAlias($brushAlias);

    /**
     * HasBrushIdentifier.
     *
     * @param string $brushIdentifier Brush identifier
     *
     * @return bool
     */
    public function hasBrushIdentifier($brushIdentifier);

    /**
     * HasBrushAlias.
     *
     * @param string $brushAlias Brush alias
     *
     * @return bool
     */
    public function hasBrushAlias($brushAlias);

    /**
     * GetBrushIdentifierAliasAndLabel.
     *
     * @param string $brushIdentifier Brush identifier
     *
     * @return array
     */
    public function getBrushIdentifierAliasAndLabel($brushIdentifier);

    /**
     * GetAutoloaderBrushMap.
     *
     * @return array
     */
    public function getAutoloaderBrushMap();

    /**
     * GetClassAttributeString.
     *
     * @param \FelixNagel\Beautyofcode\Domain\Model\Flexform $flexform Flexform
     *
     * @return string
     */
    public function getClassAttributeString(\FelixNagel\Beautyofcode\Domain\Model\Flexform $flexform);
}
