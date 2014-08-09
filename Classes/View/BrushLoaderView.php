<?php
namespace TYPO3\Beautyofcode\View;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * BrushLoaderView
 *
 * @package \TYPO3\Beautyofcode\View
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushLoaderView extends \TYPO3\CMS\Fluid\View\StandaloneView {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected $signalSlotDispatcher;

	/**
	 *
	 * @var string
	 */
	protected $library;

	/**
	 * injectSignalSlotDispatcher
	 *
	 * @param \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
	 * @return void
	 */
	public function injectSignalSlotDispatcher(
		\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
	) {
		$this->signalSlotDispatcher = $signalSlotDispatcher;
	}

	/**
	 * setLibrary
	 *
	 * @param string $library
	 * @return void
	 */
	public function setLibrary($library) {
		$this->library = $library;
	}

	/**
	 * getLibrary
	 *
	 * @return string
	 */
	public function getLibrary() {
		return $this->library;
	}

	/**
	 * initializeView
	 *
	 * @return void
	 */
	public function initializeView() {
		parent::initializeView();

		$this->layoutRootPath = ExtensionManagementUtility::extPath(
			'beautyofcode',
			'Resources/Private/BrushLoader/Layouts/'
		);
		$this->partialRootPath = ExtensionManagementUtility::extPath(
			'beautyofcode',
			'Resources/Private/BrushLoader/Partials/'
		);
		$this->templatePathAndFilename = ExtensionManagementUtility::extPath(
			'beautyofcode',
			'Resources/Private/BrushLoader/Templates/' . $this->library . '.html'
		);

		$this->signalSlotDispatcher->dispatch(
			__CLASS__,
			'overridePaths',
			array(
				$this
			)
		);
	}
}