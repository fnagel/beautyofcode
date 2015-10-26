<?php
namespace TYPO3\Beautyofcode\Configuration\Wizard;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class that adds the wizard icon.
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Configuration\Wizard
 */
class NewContentElementWizard {

	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems: The wizard items
	 *
	 * @return array Modified array with wizard items
	 */
	public function proc($wizardItems) {
		global $LANG;

		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_beautyofcode_pi1'] = array(
			'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('beautyofcode') . 'Resources/Public/Images/ce_wiz.gif',
			'title' => $LANG->getLLL('tt_content.list_type_pi1', $LL),
			'description' => $LANG->getLLL('wiz_description', $LL),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=beautyofcode_contentrenderer'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
	 *
	 * @return The array with language labels
	 */
	protected function includeLocalLang() {
		$llFile = 'EXT:beautyofcode/Resources/Private/Language/locallang_db.xml';
		$LOCAL_LANG = \TYPO3\CMS\Core\Utility\GeneralUtility::readLLfile($llFile, $GLOBALS['LANG']->lang);

		return $LOCAL_LANG;
	}
}
