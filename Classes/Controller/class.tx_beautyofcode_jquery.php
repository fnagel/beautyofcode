<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2013 Felix Nagel (info@felixnagel.com)
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Plugin 'Sourcecode (beautyOfCode)' for the 'beautyofcode' extension.
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class boc_jquery {
	var $prefixId  = 'tx_beautyofcode_pi1';		// Same as class name
	var $scriptRelPath = 'Classes/Controller/class.tx_beautyofcode_jquery.php';	// Path to this script relative to the extension dir.
	var $extKey = 'beautyofcode';	// The extension key.
	var $pi_checkCHash = TRUE;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf) {
		$this->conf = $conf;
		$libraryPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($this->extKey, 'Classes/Utility/class.tx_beautyofcode_div.php');
		\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce($libraryPath);
		$this->boc_div = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_boc_div');
	}

	/**
	 * Function set header data (JS files and JS HTML)
	 */
	public function setHeaderData() {
		// please note only the jquery core js is included by t3jquery.
		// All other components added manually cause of more flexibility
		if (T3JQUERY === TRUE) {
			// add jQuery core by t3jquery extension
			tx_t3jquery::addJqJS();
		} else {
			// add jQuery core manually if defined
			if ($this->conf['jquery.']['addjQuery'] > 0) {
				$GLOBALS['TSFE']->getPageRenderer()->addJsLibrary(
					$this->prefixId . "_jquery",
					$GLOBALS['TSFE']->tmpl->getFileName("EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery-1.3.2.min.js")
				);
			}
		}

		// add jquery.beautyOfCode.js
		$GLOBALS['TSFE']->getPageRenderer()->addJsLibrary(
			$this->prefixId . "_boc",
			$this->boc_div->makeAbsolutePath(trim($this->conf['jquery.']['scriptUrl']))
		);

		// get defaults
		$defaults = $this->getDefaults();

		// choose jQuery function selector
		$jQvar = ($this->conf['jQueryNoConflict']) ? "jQuery" : "$";

		// additional selector? (not supported by JS yet)
		$jQuerySelector = (strlen(trim($this->conf['jQuerySelector'])) > 0) ? trim($this->conf['jQuerySelector']) . ' ' : FALSE;

		// get language strings
		$language = $this->getLanguageStrings();

		$jsCode = "\n";
		// add noConflict jQuery code
		if ($this->conf['jQueryNoConflict']) {
			$jsCode .= $jQvar.'.noConflict();'."\n";
		}
		$jsCode .= $jQvar . $this->conf['jQueryOnReadyCallback'] . "\n";
		$jsCode .= "\t" . $jQvar . '.beautyOfCode.init({' . "\n";
		if (!empty($this->conf['jquery.']['baseUrl'])) $jsCode .= "\t\t" . 'baseUrl: "' . \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $this->boc_div->makeAbsolutePath(trim($this->conf['jquery.']['baseUrl'])) . '",' . "\n";
		if (!empty($this->conf['jquery.']['scripts'])) $jsCode .= "\t\t" . 'scripts: "' . trim($this->conf['jquery.']['scripts']) . '",' . "\n";
		if (!empty($this->conf['jquery.']['styles'])) $jsCode .= "\t\t".'styles: "'.trim($this->conf['jquery.']['styles']).'",'."\n";
		if (strlen(trim($this->conf['theme']))> 0) $jsCode .= "\t\t" . 'theme: "' . trim($this->conf['theme']) . '",' . "\n";
		if (!empty($defaults)) $jsCode .= "\t\t" . 'defaults: {' . $defaults . '},' . "\n";
		if ($language) $jsCode .= "\t\t".'config: { ' . $language . "\n\t\t },\n";
		// add a custom jQuery selector
		if ($jQuerySelector) {
			$jsCode .= "\t\t" . 'ready: function() {' . "\n";
			$jsCode .= "\t\t\t" . $jQvar . '("'. trim($this->conf['jQuery.']['selector']) . ' pre.code:has(code[class])").beautifyCode();' . "\n";
			$jsCode .= "\t\t" . '},' . "\n";
		}
		$jsCode .= "\t\t" . 'brushes: ["Plain"'. $this->getBrushes() .']' . "\n";
		$jsCode .= "\t" . '});' . "\n";
		$jsCode .= '});' . "\n";

		// add hook to add custom JS in header
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addJS_setHeaderData']) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addJS_setHeaderData'] as $_funcRef) {
				if ($_funcRef) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($_funcRef, $jsCode, $this);
				}
			}
		}

		// add js init
		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode($this->extKey, $jsCode);
	}

	/**
	 * Function to set HTML code
	 *
	 * @return	string  contains build html with sourcecode
	 */
	public function getHTMLContent() {
		$HTML = array();
		// make html
		$lang = ($this->values['lang']) ? $this->values['lang'] : "plain";
		$HTML['code'] = '';
		$HTML['code'] .= "\n" . '<pre class="code"><code class="' . $lang . $this->getCssConfig() . '">' . "\n";
		$HTML['code'] .= htmlspecialchars($this->values['code']) . "\n";
		$HTML['code'] .= '</code></pre>' . "\n";
		// make label
		$HTML['label'] = ($this->conf['showLabel'] && trim($this->values['label']) != "") ? trim($this->values['label']) : "";

		return $HTML;
	}

	/**
	 * Function to solve CSSconfiguration which overwrites TS configuration
	 *
	 * @return	string  space seperated CSS classes
	 */
	public function getCssConfig() {
		$string = '';
		if (is_array($this->values['css'])) {
			// built brushes string
			$string = '';
			foreach($this->values['css'] AS $config => $configValue) {
				if ($configValue != "" && $configValue != "auto") {
					// highlight range
					if ($config == "highlight") {
						$string .= " boc-highlight[" . \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($configValue) . "]";
					} else {
						if ($configValue) $string .= " boc-" . $config;
						else $string .= " boc-no-" . $config;
					}
				}
			}
		}
		return $string;
	}

	/**
	 * Function to solve brushes
	 *
	 * @return	string  comma seperated list of the brushes with double quotes
	 */
	public function getBrushes() {
		// built brushes string
		$temp = '';
		if (strlen($this->conf['brushes']) > 0) {
			$brushesArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(",", $this->conf['brushes'], TRUE);
			foreach ($brushesArray as $brush) {
				$temp .= ',"' . $brush . '"';
			}
		}
		return $temp;
	}

	/**
	 * Function to solve defaults
	 *
	 * @return	string  comma seperated list list of the brushes with double quotes with no ending ,
	 */
	public function getDefaults() {
		$temp = '';
		if (is_array($this->conf['defaults.']) > 0) {
			foreach ($this->conf['defaults.'] as $key => $value) {
				$temp .= '"' . $key . '": ' . $value . ',';
			}
			$temp = substr($temp, 0, -1);
		}
		return $temp;
	}

	/**
	 * Function to solve language strings
	 *
	 * @return	string  object with language strings
	 */
	public function getLanguageStrings() {
		$temp = '';
		if (is_array($this->conf['config.']['strings.']) > 0) {
			$temp .= "\n\t\t\t" . 'strings: {' . "\n";
			foreach ($this->conf['config.']['strings.'] as $key => $value) {
				$temp .= "\t\t\t\t" . trim($key) . ': "' . trim($value) . '",' . "\n";
			}
			$temp .= "\t\t\t" . '}' . "\n";
		}
		return $temp;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1.php']);
}

?>