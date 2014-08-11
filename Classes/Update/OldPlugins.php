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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * The old plugins update handling class
 *
 * @package \TYPO3\Beautyofode\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class OldPlugins extends \TYPO3\Beautyofcode\Update\AbstractUpdate {

	/**
	 *
	 * @var integer
	 */
	protected $countOldPlugins;

	/**
	 *
	 * @var strng
	 */
	protected $template = 'EXT:beautyofcode/Resources/Private/Templates/Update/OldPlugins.html';

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->countOldPlugins = $this->db->exec_SELECTcountRows(
			'*',
			'tt_content',
			'list_type = "beautyofcode_pi1"'
		);

		$templatePath = GeneralUtility::getFileAbsFileName($this->template);
		$this->view->setTemplatePathAndFilename($templatePath);
	}

	public function getInformation() {
		$this->view->assign('section', 'Information');

		$this->view->assign('mustExecute', $this->mustExecute());
		$this->view->assign('countOldPlugins', $this->countOldPlugins);

		return $this->view->render();
	}

	/**
	 *
	 * @return boolean
	 */
	protected function mustExecute() {
		return 0 < $this->countOldPlugins;
	}

	/**
	 *
	 * @return string
	 */
	public function execute() {
		$this->view->assign('section', 'Execute');

		if ($this->hasUpdateInstruction('oldPlugins') && $this->mustExecute()) {
			$this->db->exec_UPDATEquery(
				'tt_content',
				'list_type = "beautyofcode_pi1"',
				array(
					'list_type' => 'beautyofcode_contentrenderer'
				)
			);

			$this->view->assign('countOldPlugins', $this->countOldPlugins);
		}

		return $this->view->render();
	}
}