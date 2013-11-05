<?php
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

require_once(PATH_tslib.'class.tslib_pibase.php');

// check for loaded t3jquery extension
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery')) {
	require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery') . 'class.tx_t3jquery.php');
}

/**
 * Plugin 'Sourcecode (beautyOfCode)' for the 'beautyofcode' extension.
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class tx_beautyofcode_pi1 extends tslib_pibase {
	var $prefixId = 'tx_beautyofcode_pi1';		// Same as class name
	var $scriptRelPath = 'Classes/Controller/class.tx_beautyofcode_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'beautyofcode';	// The extension key.
	var $pi_checkCHash = TRUE;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */

	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		// init vars
		$this->error = FALSE;

		// check for static template
		$this->checkForStaticTempl();

		// check for template file
		$this->getTemplateFile();

		// proceed if no error is saved
		if ($this->error) {
			return '<div style="text-align: left; text-size: 12px; color: red; margin: 10px; padding: 10px; background: white; border: 3px solid red;"><strong>Beauty of Code Extension Error</strong><br /><p><em>PID: ' . $this->cObj->data['pid'] . '</em><br /><em>UID: ' . $this->cObj->data['uid'] . '</em></p><p>' . implode("<br />", $this->error) . '</p></div>';
		}

		// parse XML data into php array
		$this->pi_initPIflexForm();

		// use Syntax Highlighter v2 (jQuery 'beautyOfCode' driven) or v3 (standalone)
		if ($this->conf['version'] == 'jquery') {
			\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($this->extKey, 'Classes/Controller/class.tx_beautyofcode_jquery.php'));
			$this->boc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('boc_jquery');
		} else {
			\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($this->extKey, 'Classes/Controller/class.tx_beautyofcode_standalone.php'));
			$this->boc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('boc_standalone');
		}

		// init need version class
		$this->boc->main($content, $conf);

		// get configs (API or Flexform)
		if (is_array($this->values)) {
			$this->boc->values = $this->values;
		} else {
			// copy flexform values into our boc instance
			$this->boc->values = $this->getflexFormValue();
		}

		// set Header data
		$this->boc->setHeaderData();

		//create preview
		$this->makePreview();

		return $this->getHTML();
	}

	/**
	 * Function to get get flexform values
	 *
	 * @return	array containing flex form valuees
	 */
	public function getflexFormValue() {
		$temp = array();
		$temp['css'] = array();

		// get flexform values
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$temp['label'] = $this->pi_getFFvalue($piFlexForm, 'cLabel', 'sDEF');
		$temp['lang'] = $this->pi_getFFvalue($piFlexForm, 'cLang', 'sDEF');
		$temp['code'] = $this->pi_getFFvalue($piFlexForm, 'cCode', 'sDEF');

		$temp['css']['highlight'] = $this->pi_getFFvalue($piFlexForm, 'cHighlight', 'sOPTIONS');
		$temp['css']['gutter'] = $this->pi_getFFvalue($piFlexForm, 'cGutter', 'sOPTIONS');
		$temp['css']['toolbar'] = $this->pi_getFFvalue($piFlexForm, 'cToolbar', 'sOPTIONS');
		$temp['css']['collapse'] = $this->pi_getFFvalue($piFlexForm, 'cCollapse', 'sOPTIONS');

		return $temp;
	}

	/**
	 * Function to make preview in BE FCE Element
	 *
	 */
	public function makePreview() {
		if (strlen($this->cObj->data['header']) > 0) {
			$preview = ($this->flexFormValue['label']) ? "[" . $this->flexFormValue['lang'] . "] " . substr($this->flexFormValue['label'], 0, 60) : "[" . $this->flexFormValue['lang'] . "] " . htmlspecialchars(substr($this->flexFormValue['code'], 0, 60));
			$bodytext = $this->cObj->data['bodytext'];
			if ($bodytext != $preview) {
				// copy the code to bodytext for preview in BE
				$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tt_content',
					'uid=' . $this->cObj->data['uid'],
					array('bodytext' => $preview)
				);
			}
		}
	}

	/**
	 * Function to parse the template
	 *
	 */
	public function getHTML() {
		$HTML = $this->boc->getHTMLContent();
		// Extract subparts from the main template
		$templateMain = $this->cObj->getSubpart($this->templateHtml, '###TEMPLATE_MAIN###');

		// only generate label when set
		if ($HTML['label'] != "") {
			// Extract subparts from the label template
			$templateLabel = $this->cObj->getSubpart($this->templateHtml, '###TEMPLATE_LABEL###');

			// fill label marker array
			$markerArrayLabel['###LABEL###'] = $HTML['label'];
			// replace markers in the label template
			$HTML['label'] = $this->cObj->substituteMarkerArray($templateLabel, $markerArrayLabel);
		}

		// fill main marker array
		$markerArrayMain['###CODE###'] = $HTML['code'];
		$markerArrayMain['###LABEL###'] = $HTML['label'];

		// hook to add possibility to add custom marker in template
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarker_getHTML']) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addMarker_getHTML'] as $_funcRef) {
				if ($_funcRef) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($_funcRef, $markerArrayMain, $this);
				}
			}
		}

		// replace markers in the main template
		$content = $this->cObj->substituteMarkerArray($templateMain, $markerArrayMain);

		return $content;
	}

	/**
	 * Function to fetch the template file
	 *
	 */
	public function getTemplateFile() {
		// Get the template
		$templateFile = (strlen(trim($this->conf['templateFile'])) > 0) ? trim($this->conf['templateFile']) : "EXT:beautyofcode/Resources/Private/Templates/template.html";
		$this->templateHtml = $this->cObj->fileResource($templateFile);

		if (!$this->templateHtml) {
			$this->handleError('Error while fetching the template file: <em>' . $templateFile . '</em>');
		}
	}

	/**
	 * Function to check if static template is included
	 *
	 * @return	void
	 */
	public function checkForStaticTempl() {
		if (is_array($this->conf)
			&& !isset($this->conf["noConflict"])
			&& !isset($this->conf["addjQuery"])
			&& !isset($this->conf["showLabel"])
			&& !isset($this->conf["theme"])
			&& !isset($this->conf["brushes"])
			&& !isset($this->conf["defaults"])
		) {
			$this->handleError('Please add the static TS to your main template.');
		}
	}

	/**
	 * Handles error output for frontend and TYPO3 logging
	 *
	 * @param	string	Message to output
	 * @return	void
	 * @see	t3lib::devLog()
	 * @see	t3lib_div::sysLog()
	 */
	public function handleError($msg) {
		// prepare FE output
		if ($this->error === FALSE) {
			$this->error = array();
		}
		$this->error[] = $msg . "<br />";

		\TYPO3\CMS\Core\Utility\GeneralUtility::sysLog($msg, $this->extKey, 3); // error
		// write dev log if enabled
		if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['enable_DLOG']) {
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog($msg, $this->extKey, 3); // fatal error
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1.php']);
}
?>