<?php
namespace TYPO3\Beautyofcode\Highlighter\Configuration;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
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

use TYPO3\Beautyofcode\View\BrushLoaderView;

/**
 * AbstractConfiguration
 *
 * @package \TYPO3\Beautyofcode\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
abstract class AbstractConfiguration implements \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface {

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 *
	 * @var BrushLoaderView
	 */
	protected $brushLoaderView;

	/**
	 * __construct
	 *
	 * @param array $settings
	 * @return void
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * injectBrushLoaderView
	 *
	 * @param BrushLoaderView $brushLoaderView
	 * @return void
	 */
	public function injectBrushLoaderView(BrushLoaderView $brushLoaderView) {
		$this->brushLoaderView = $brushLoaderView;
		$this->brushLoaderView->setLibrary($this->settings['library']);
		$this->brushLoaderView->initializeView();
		$this->brushLoaderView->assign('settings', $this->settings);
	}

	/**
	 * getFailSafeBrushAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getFailSafeBrushAlias($brushAlias) {
		if ($this->hasBrushAlias($brushAlias)) {
			return $brushAlias;
		}

		foreach ($this->failSafeBrushAliasMap as $foreignLibraryMap) {
			if (isset($foreignLibraryMap[$brushAlias])) {
				$failSafeBrushAlias = $foreignLibraryMap[$brushAlias];
				break;
			}
		}

		return $failSafeBrushAlias;
	}

	/**
	 * hasBrushAlias
	 *
	 * @param string $brushAlias
	 * @return boolean
	 */
	protected function hasBrushAlias($brushAlias) {
		foreach ($this->brushIdentifierAliasMap as $alias) {
			if ($alias === $brushAlias) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * addRegisteredBrushes
	 *
	 * @param array $brushStack
	 * @return void
	 */
	public function addRegisteredBrushes(array $brushStack = array()) {
		$brushes = $brushStack;

		$this->brushLoaderView->assign('brushes', $brushes);
		$this->brushLoaderView->render();
	}

	/**
	 * getBrushAliasByIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return string
	 */
	public function getBrushAliasByIdentifier($brushIdentifier) {
		if (isset($this->brushIdentifierAliasMap[$brushIdentifier])) {
			return $this->brushIdentifierAliasMap[$brushIdentifier];
		}

		return $brushIdentifier;
	}
}
?>