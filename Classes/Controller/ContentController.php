<?php
namespace TYPO3\Beautyofcode\Controller;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * The frontend plugin controller for the syntaxhighlighter
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository
	 */
	protected $flexformRepository;

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository
	 */
	public function injectFlexformRepository(\TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository) {
		$this->flexformRepository = $flexformRepository;
	}

	/**
	 *
	 * @return void
	 */
	public function renderAction() {
		$flexform = $this
			->flexformRepository
			->reconstituteByContentObject(
				$this->configurationManager->getContentObject()
			);
		$flexform->setBrushes($this->settings['brushes']);
		$flexform->setTyposcriptDefaults($this->settings['defaults']);

		$this->view->assign('flexform', $flexform);
	}
}
?>