<?php
namespace TYPO3\Beautyofcode\Update;

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
 * @package \TYPO3\Beautyofcode\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
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

	public function __construct() {
		if (FALSE === isset($this->db)) {
			$this->db = $GLOBALS['TYPO3_DB'];
		}

		$this->updateInstructions = (array) \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('update');

		$this->initializeObject();
	}

	/**
	 *
	 * @param string $instructionKey
	 * @return boolean
	 */
	protected function hasUpdateInstruction($instructionKey) {
		return array_key_exists($instructionKey, $this->updateInstructions) && TRUE === (boolean) $this->updateInstructions[$instructionKey];
	}

	abstract public function initializeObject();

	abstract public function getInformation();

	abstract public function execute();
}
?>