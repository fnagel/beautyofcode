<?php

namespace FelixNagel\Beautyofcode\Utility;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Utility class for beautyofcode.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class FrontendUtility
{
    /**
     * Resolves a path prefixed with FILE: and EXT:.
     *
     * If the path can successfully be resolved to an internal (relative to PATH_site / publicPath)
     * path, the PATH_site / publicPath part is removed and the resulting path is returned.
     * If it's an external path, the input parameter is returned unchanged.
     */
    public static function makeAbsolutePath(string $dir): string
    {
        $absolutePath = '';

        $isExtensionNotation = str_starts_with($dir, 'EXT:');
        $isFileNotation = str_starts_with($dir, 'FILE:');

        if ($isFileNotation) {
            $dir = substr($dir, 5);
        }

        if ($isExtensionNotation || $isFileNotation) {
            $absolutePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($dir);
            $absolutePath = empty($absolutePath) ? '' : PathUtility::getAbsoluteWebPath($absolutePath);
        } elseif (false !== parse_url($dir)) {
            $absolutePath = $dir;
        }

        return $absolutePath;
    }

    public static function getNormalizedParams(): NormalizedParams
    {
        return static::getRequest()->getAttribute('normalizedParams');
    }

    protected static function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
