<?php
namespace TYPO3\Beautyofcode\Configuration\Wizard;

/***************************************************************
 * Copyright notice
 *
 * (c) 2010-2013 Felix Nagel (info@felixnagel.com)
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
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class that adds the wizard icon.
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Configuration\Wizard
 * @codeCoverageIgnore
 */
class NewContentElementWizard {

	/**
	 *
	 * @var \TYPO3\CMS\Lang\LanguageService
	 */
	protected $languageService;

	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems: The wizard items
	 * @return Modified array with wizard items
	 */
	public function proc($wizardItems) {
		$this->languageService = $GLOBALS['LANG'];

		$translationArray = $this->includeLocalLang();

		$wizardItems['plugins_tx_beautyofcode_pi1'] = array(
			'icon' => ExtensionManagementUtility::extRelPath('beautyofcode') . 'Resources/Public/Images/ce_wiz.gif',
			'title' => $this->languageService->getLLL('tt_content.list_type_pi1', $translationArray),
			'description' => $this->languageService->getLLL('wiz_description', $translationArray),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=beautyofcode_contentrenderer'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
	 *
	 * @return array The array with language labels
	 */
	protected function includeLocalLang() {
		$translationFile = 'EXT:beautyofcode/Resources/Private/Language/locallang_db.xml';
		$translationArray = GeneralUtility::readLLfile($translationFile, $this->languageService->lang);

		return $translationArray;
	}
}