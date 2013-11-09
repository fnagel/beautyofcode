<?php
namespace FNagel\Beautyofcode\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * The frontend plugin controller for the syntaxhighlighter
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 *
	 * @var \FNagel\Beautyofcode\Service\LibraryServiceInterface
	 */
	protected $libraryService;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 */
	protected $flexformService;

	/**
	 *
	 * @var array
	 */
	protected $flexformValues = array();

	/**
	 *
	 * @param \FNagel\Beautyofcode\Service\LibraryServiceInterface $libraryService
	 */
	public function injectLibraryService(\FNagel\Beautyofcode\Service\LibraryServiceInterface $libraryService) {
		$this->libraryService = $libraryService;
	}

	/**
	 * Injects the flexform service and populates flexform values from `pi_flexform`
	 *
	 * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexformService
	 */
	public function injectFlexformService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexformService) {
		$this->flexformService = $flexformService;
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::initializeAction()
	 */
	public function initializeAction() {
		$flexformString = $this->configurationManager->getContentObject()->data['pi_flexform'];
		$this->flexformValues = $this->flexformService->convertFlexFormContentToArray($flexformString);

		$this->libraryService->setConfigurationManager($this->configurationManager);
		$this->libraryService->load($this->settings['version']);
	}

	/**
	 *
	 * @return void
	 */
	public function renderAction() {
		$cssConfig = array(
			'highlight' => $this->flexformValues['cHighlight'],
			'gutter' => $this->flexformValues['cGutter'],
			'toolbar' => $this->flexformValues['cToolbar'],
			'collapse' => $this->flexformValues['cCollapse'],
		);

		$this->view->assignMultiple(array(
			'lang' => $this->flexformValues['cLang'],
			'label' => $this->flexformValues['cLabel'],
			'code' => $this->flexformValues['cCode'],
			'cssConfig' => $this->libraryService->getClassAttributeConfiguration($cssConfig),
		));
	}
}
?>