<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Updates tt_content records
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ext_update {

	/**
	 *
	 * @var array<\TYPO3\Beautyofcode\Update\AbstractUpdate>
	 */
	protected $updaters = array();

	/**
	 * Checks if the update script must be run
	 *
	 * @return boolean
	 */
	public function access() {
		return TRUE;
	}

	/**
	 * Executes the update script
	 *
	 * @return string
	 */
	public function main() {
		$this->injectUpdaters();

		if (NULL === \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('boc_update')) {
			$output = $this->showUpdateInformation();
		} else {
			$output = $this->update();
		}

		return $output;
	}

	/**
	 *
	 * @return void
	 */
	protected function injectUpdaters() {
		$this->updaters[] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\Beautyofcode\\Update\\OldPlugins');
		$this->updaters[] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\Beautyofcode\\Update\\LanguageSetting');

		foreach ($this->updaters as $updater) {
			/* @var $view \TYPO3\CMS\Fluid\View\StandaloneView */
			$view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');

			$updater->injectDatabaseConnection($GLOBALS['TYPO3_DB']);
			$updater->injectView($view);
			$updater->initializeObject();
		}
	}

	/**
	 * Returns a form with update information
	 *
	 * @return string
	 */
	protected function showUpdateInformation() {
		$output = '<form action="" method="post">';

		foreach ($this->updaters as $updater) {
			/* @var $updater \TYPO3\Beautyofcode\Update\AbstractUpdate */
			$output .= $updater->getInformation();
		}

		$output .= '<input type="submit" name="boc_update" value="Perform update"></form>';

		return $output;
	}

	protected function update() {
		$output = '';

		foreach ($this->updaters as $updater) {
			/* @var $updater \TYPO3\Beautyofcode\Update\AbstractUpdate */
			$output .= $updater->execute();
		}

		if ($output === '') {
			$output = 'Nothing needs to be updated.';
		}

		return $output;
	}
}