<?php

namespace FelixNagel\Beautyofcode\Core\ViewHelper;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Abstract page renderer based view helper.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
abstract class AbstractPageRendererViewHelper extends AbstractViewHelper
{
    /**
     * PageRenderer.
     *
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * InjectPageRenderer.
     *
     * @param PageRenderer $pageRenderer PageRenderer
     */
    public function injectPageRenderer(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }
}
