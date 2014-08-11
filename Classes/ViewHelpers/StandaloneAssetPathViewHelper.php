<?php
namespace TYPO3\Beautyofcode\ViewHelpers;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * VH for the standalone scripts/styles asset paths.
 *
 * @package \TYPO3\Beautyofcode\ViewHelpers
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class StandaloneAssetPathViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @var string
	 */
	const DEFAULT_BASE_URL = 'http://alexgorbatchev.com/';

	/**
	 *
	 * @var string
	 */
	const DEFAULT_RESOURCE_PATH_PREFIX = 'pub/sh/current/';

	/**
	 * Valid resource types
	 *
	 * @var array
	 */
	protected $validTypes = array('scripts', 'styles');

	/**
	 * initializeArguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument(
			'baseUrl',
			'string',
			'baseUrl of the assets path',
			FALSE,
			self::DEFAULT_BASE_URL
		);
		$this->registerArgument(
			'resourcePath',
			'string',
			'The path of the resource, relative to baseUrl',
			FALSE,
			self::DEFAULT_RESOURCE_PATH_PREFIX
		);
		$this->registerArgument(
			'type',
			'string',
			'The type of the asset, must be either `scripts` or `styles`.',
			TRUE
		);
	}

	/**
	 * initialize
	 *
	 * @return void
	 */
	public function initialize() {
		if ('' === trim($this->arguments['baseUrl'])) {
			$this->arguments['baseUrl'] = self::DEFAULT_BASE_URL;
		}

		if ('' === trim($this->arguments['resourcePath'])) {
			$this->arguments['resourcePath'] = self::DEFAULT_RESOURCE_PATH_PREFIX . $this->arguments['type'] . '/';
		}

		if (FALSE === in_array($this->arguments['type'], $this->validTypes)) {
			throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('The type argument must be one of ' . implode(', ', $this->validTypes) . '.', 1389366818);
		}
	}

	/**
	 * render
	 *
	 * @return string
	 */
	public function render() {
		return $this->arguments['baseUrl'] . $this->arguments['resourcePath'];
	}
}