<?php

namespace FelixNagel\Beautyofcode\Utility;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Utility class for beautyofcode.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class GeneralUtility
{
    /**
     * Resolves a path prefixed with FILE: and EXT:.
     *
     * If the path can successfully be resolved to an internal (relative to PATH_site / publicPath)
     * path, the PATH_site / publicPath part is removed and the resulting path is returned.
     * If its an external path, the input parameter is returned unchanged.
     *
     * @param string $dir Path to directory
     *
     * @return string
     */
    public static function makeAbsolutePath($dir)
    {
        $absolutePath = '';

        $isExtensionNotation = str_starts_with($dir, 'EXT:');
        $isFileNotation = str_starts_with($dir, 'FILE:');

        if ($isFileNotation) {
            $dir = substr($dir, 5);
        }

        if ($isExtensionNotation || $isFileNotation) {
            $absolutePath = PathUtility::getAbsoluteWebPath(
                \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($dir)
            );
        } elseif (false !== parse_url($dir)) {
            $absolutePath = $dir;
        }

        return $absolutePath;
    }
}
