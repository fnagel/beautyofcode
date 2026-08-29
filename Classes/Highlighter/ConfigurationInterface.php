<?php

namespace FelixNagel\Beautyofcode\Highlighter;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Domain\Model\Flexform;

/**
 * ConfigurationInterface.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
interface ConfigurationInterface
{
    public function getFailSafeBrushAlias(string $brushAlias): string;

    public function hasBrushIdentifier(string $brushIdentifier): bool;

    public function hasBrushAlias(string $brushAlias): bool;

    public function getBrushIdentifierAliasAndLabel(string $brushIdentifier): array;

    public function getAutoloaderBrushMap(): array;

    public function getClassAttributeString(Flexform $flexform): string;
}
