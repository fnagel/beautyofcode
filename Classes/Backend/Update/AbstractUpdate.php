<?php
namespace TYPO3\Beautyofcode\Backend\Update;

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

/**
 * The abstract update class
 *
 * @package \TYPO3\Beautyofcode\Backend\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
abstract class AbstractUpdate {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	/**
	 * Contains an array of update instructions, incoming from _GP['update']
	 *
	 * @var array
	 */
	protected $updateInstructions = array();

	/**
	 *
	 * @var \TYPO3\CMS\Fluid\View\StandaloneView
	 */
	protected $view;

	/**
	 * __construct
	 *
	 * @return \TYPO3\Beautyofcode\Backend\Update\AbstractUpdate
	 */
	public function __construct() {
		$this->updateInstructions = (array) \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('update');
	}

	/**
	 * injectDatabaseConnection
	 *
	 * @param \TYPO3\CMS\Core\Database\DatabaseConnection $db
	 * @return void
	 */
	public function injectDatabaseConnection(\TYPO3\CMS\Core\Database\DatabaseConnection $db) {
		$this->db = $db;
	}

	/**
	 * injectView
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
	 * @return void
	 */
	public function injectView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view = NULL) {
		$this->view = $view;
	}

	/**
	 * hasUpdateInstruction
	 *
	 * @param string $instructionKey
	 * @return boolean
	 */
	protected function hasUpdateInstruction($instructionKey) {
		return array_key_exists($instructionKey, $this->updateInstructions) && TRUE === (boolean) $this->updateInstructions[$instructionKey];
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	abstract public function initializeObject();

	/**
	 * getInformation
	 *
	 * @return string
	 */
	abstract public function getInformation();

	/**
	 * execute
	 *
	 * @return string
	 */
	abstract public function execute();
}