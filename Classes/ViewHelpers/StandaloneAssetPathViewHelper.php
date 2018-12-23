<?php

namespace TYPO3\Beautyofcode\ViewHelpers;

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
 * VH for the standalone scripts/styles asset paths.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class StandaloneAssetPathViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Default base url.
     *
     * @var string
     */
    const DEFAULT_BASE_URL = 'http://alexgorbatchev.com/';

    /**
     * Default resource path prefix.
     *
     * @var string
     */
    const DEFAULT_RESOURCE_PATH_PREFIX = 'pub/sh/current/';

    /**
     * Valid resource types.
     *
     * @var array
     */
    protected $validTypes = ['scripts', 'styles'];

    /**
     * InitializeArguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('baseUrl', 'string', 'baseUrl of the assets path', false, self::DEFAULT_BASE_URL);
        $this->registerArgument('resourcePath', 'string', 'The path of the resource, relative to baseUrl', false, self::DEFAULT_RESOURCE_PATH_PREFIX);
        $this->registerArgument('type', 'string', 'The type of the asset, must be either `scripts` or `styles`.', true);
    }

    /**
     * Initialize.
     *
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function initialize()
    {
        if ('' === trim($this->arguments['baseUrl'])) {
            $this->arguments['baseUrl'] = self::DEFAULT_BASE_URL;
        }

        if ('' === trim($this->arguments['resourcePath'])) {
            $this->arguments['resourcePath'] = self::DEFAULT_RESOURCE_PATH_PREFIX . $this->arguments['type'] . '/';
        }

        if (false === in_array($this->arguments['type'], $this->validTypes)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('The type argument must be one of ' . implode(', ', $this->validTypes) . '.', 1389366818);
        }
    }

    /**
     * Renders the view helper.
     *
     * @return string
     */
    public function render()
    {
        return $this->arguments['baseUrl'] . $this->arguments['resourcePath'];
    }
}
