<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 * VH for the standalone scripts/styles asset paths.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class StandaloneAssetPathViewHelper extends AbstractViewHelper
{
    /**
     * Default base url.
     *
     * @var string
     */
    public const DEFAULT_BASE_URL = 'http://alexgorbatchev.com/';

    /**
     * Default resource path prefix.
     *
     * @var string
     */
    public const DEFAULT_RESOURCE_PATH_PREFIX = 'pub/sh/current/';

    /**
     * Valid resource types.
     *
     * @var array
     */
    protected $validTypes = ['scripts', 'styles'];

    /**
     * InitializeArguments.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('baseUrl', 'string', 'baseUrl of the assets path', false, self::DEFAULT_BASE_URL);
        $this->registerArgument(
            'resourcePath',
            'string',
            'The path of the resource, relative to baseUrl',
            false,
            self::DEFAULT_RESOURCE_PATH_PREFIX
        );
        $this->registerArgument('type', 'string', 'The type of the asset, must be either `scripts` or `styles`.', true);
    }

    /**
     * Initialize.
     *
     * @throws Exception
     */
    public function initialize(): void
    {
        if (trim($this->arguments['baseUrl']) === '') {
            $this->arguments['baseUrl'] = self::DEFAULT_BASE_URL;
        }

        if (trim($this->arguments['resourcePath']) === '') {
            $this->arguments['resourcePath'] = self::DEFAULT_RESOURCE_PATH_PREFIX . $this->arguments['type'] . '/';
        }

        if (!in_array($this->arguments['type'], $this->validTypes)) {
            throw new Exception(
                'The type argument must be one of ' . implode(', ', $this->validTypes) . '.',
                1389366818
            );
        }
    }

    /**
     * Renders the view helper.
     */
    public function render(): string
    {
        return $this->arguments['baseUrl'] . $this->arguments['resourcePath'];
    }
}
