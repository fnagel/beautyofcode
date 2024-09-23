<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Closure;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use FelixNagel\Beautyofcode\Utility\GeneralUtility;

/**
 * FileExistsViewHelper.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class FileExistsViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('file', 'string', 'Path to the file');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return file_exists(Environment::getPublicPath().GeneralUtility::makeAbsolutePath($arguments['file']));
    }
}
