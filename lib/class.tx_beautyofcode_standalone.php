<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2012 Felix Nagel (info@felixnagel.com)
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
class boc_standalone {
	var $prefixId      = 'tx_beautyofcode_pi1';		// Same as class name
	var $scriptRelPath = 'lib/class.tx_beautyofcode_standalone.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'beautyofcode';	// The extension key.
	var $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf) {
		$this->conf = $conf;
		t3lib_div::requireOnce(t3lib_extMgm::extPath($this->extKey, 'lib/class.tx_beautyofcode_div.php'));
		$this->boc_div = t3lib_div::makeInstance('tx_boc_div');
	}

	/**
	 * Function set header data (JS files and JS HTML)
	 */
	public function setHeaderData() {

		// check if we use online hosting or local files
		if (strlen(trim($this->conf["standalone."]["baseUrl"])) > 0  && strlen(trim($this->conf["standalone."]["styles"])) > 0 && strlen(trim($this->conf["standalone."]["scripts"])) > 0 ) {
			$this->filePathBase = $this->boc_div->makeAbsolutePath($this->conf["standalone."]["baseUrl"]);
			$this->filePathScripts = trim($this->conf["standalone."]["scripts"]);
			$this->filePathStyles = trim($this->conf["standalone."]["styles"]);
		} else {
			$this->filePathBase = "http://alexgorbatchev.com/";
			$this->filePathScripts = "pub/sh/current/scripts/";
			$this->filePathStyles = "pub/sh/current/styles/";
		}

		// add css files
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile( $this->filePathBase . $this->filePathStyles . 'shCore.css');

		// add theme in header
		if (strlen(trim($this->conf["theme"])) > 0) {
			$cssStyleFile = 'shTheme' . trim($this->conf["theme"]) . '.css';
		} else {
			$cssStyleFile = 'shCoreDefault.css';
		}
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile( $this->filePathBase . $this->filePathStyles . $cssStyleFile);

		// add js files
		$GLOBALS['TSFE']->getPageRenderer()->addJsLibrary($this->prefixId . '_JS_shCoreJS', $this->filePathBase . $this->filePathScripts . 'shCore.js');
		$GLOBALS['TSFE']->getPageRenderer()->addJsLibrary($this->prefixId . '_JS_shAutoloader', $this->filePathBase . $this->filePathScripts . 'shAutoloader.js');

		// get defaults
		$defaults = $this->getDefaults();
		// init SyntaxHighlighter autoloader
		$jsCodeSh = "";
		$jsCodeSh .= '	SyntaxHighlighter.autoloader('."\n";
		$jsCodeSh .= $this->getBrushes();
		$jsCodeSh .= '	);'."\n";
		// set SyntaxHighlighter options
		$jsCodeSh 							.= $this->getLanguageStrings() ."\n";
		if (!empty($defaults)) $jsCodeSh 	.= $defaults ."\n";
		// init SyntaxHighlighter
		$jsCodeSh .= " 	SyntaxHighlighter.all();"."\n";

		// add hook to add custom JS in header
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addJS_setHeaderData']) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['addJS_setHeaderData'] as $_funcRef) {
				if ($_funcRef) {
					t3lib_div::callUserFunction($_funcRef, $jsCodeSh, $this);
				}
			}
		}

		// use JS domReady if TYPO3 version is insufficient or if option is enabled
		$jsCode = "\n";
		if ($this->conf["standalone."]["includeAsDomReady"] == "jquery") {
			// add jquery domready event
			$jQvar = ($this->conf['jQueryNoConflict']) ? "jQuery" : "$";
			// add noConflict jQuery code
			if ($this->conf['jQueryNoConflict']) {
				$jsCode	.= $jQvar.'.noConflict();'."\n";
			}
			$jsCode 	.= $jQvar.$this->conf['jQueryOnReadyCallback']."\n";
			$jsCode 	.= $jsCodeSh;
			$jsCode 	.= '});'."\n";
		} else {
			// add js domready event manually
			// http://phpperformance.de/javascript-event-onload-und-die-bessere-alternative/
			$jsCode .= "window.onDomReady = initReady;"."\n";
			$jsCode .= "function initReady(fn) {"."\n";
			$jsCode .= "	if(document.addEventListener) {"."\n";
			$jsCode .= "		document.addEventListener('DOMContentLoaded', fn, false);"."\n";
			$jsCode .= "	} else {"."\n";
			$jsCode .= "		document.onreadystatechange = function(){readyState(fn)}"."\n";
			$jsCode .= "	}"."\n";
			$jsCode .= "}"."\n";
			$jsCode .= "function readyState(func) {"."\n";
			$jsCode .= "	if(document.readyState == 'interactive' || document.readyState == 'complete') {"."\n";
			$jsCode .= "		func();"."\n";
			$jsCode .= "	}"."\n";
			$jsCode .= "}"."\n";
			$jsCode .= "window.onDomReady(onReady);"."\n";
			$jsCode .= "function onReady() {"."\n";
			$jsCode .= $jsCodeSh;
			$jsCode .= "};"."\n";
		}
		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode($this->extKey . "_JS_init", $jsCode);
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
		$HTML['code'] .= "\n".'<pre class="brush: '. $lang . $this->getCssConfig() . '">'."\n";
		$HTML['code'] .= htmlspecialchars($this->values['code'])."\n";
		$HTML['code'] .= '</pre>'."\n";

		// make label
		$HTML['label'] = ($this->conf['showLabel'] && trim($this->values['label']) != "") ? trim($this->values['label']) : "";

		return $HTML;
	}

	/**
	 * Function to solve brushes
	 *
	 * @return	string  javascript array with strings containing the autoload parameter
	 */
	public function getBrushes() {
		// built brushes string
		$temp = "\t\t" . "'plain" . "\t" . $this->filePathBase . $this->filePathScripts . "shBrushPlain.js'";
		if (strlen($this->conf['brushes'])>0) {
			$brushesArray = t3lib_div::trimExplode(",", $this->conf['brushes'], true);
			foreach($brushesArray AS $brush) {
				$temp .= "," . "\n";
				$temp .= "\t\t" . "'" . $this->chooseCSSTag($brush) . "\t" . $this->filePathBase . $this->filePathScripts . "shBrush" . $brush . ".js'";
			}
		}
		$temp .= "\n";
		return  $temp;
	}

	/**
	 * Function to solve autoload CSS string
	 *
	 * @return	string  CSS string matching brush file name
	 */
	public function chooseCSSTag($brush) {
		switch ($brush) {
			case "AS3":
				$trigger = "actionscript3";
			break;
			case "Bash":
				$trigger = "bash";
			break;
			case "ColdFusion":
				$trigger = "coldfusion";
			break;
			case "Cpp":
				$trigger = "cpp";
			break;
			case "CSharp":
				$trigger = "csharp";
			break;
			case "Css":
				$trigger = "css";
			break;
			case "Delphi":
				$trigger = "delphi";
			break;
			case "Diff":
				$trigger = "diff";
			break;
			case "Erlang":
				$trigger = "erlang";
			break;
			case "Groovy":
				$trigger = "groovy";
			break;
			case "Java":
				$trigger = "java";
			break;
			case "JavaFX":
				$trigger = "javafx";
			break;
			case "JScript":
				$trigger = "javascript";
			break;
			case "Perl":
				$trigger = "perl";
			break;
			case "Php":
				$trigger = "php";
			break;
			case "PowerShell":
				$trigger = "powershell";
			break;
			case "Python":
				$trigger = "python";
			break;
			case "Ruby":
				$trigger = "ruby";
			break;
			case "Scala":
				$trigger = "scala";
			break;
			case "Sql":
				$trigger = "sql";
			break;
			case "Typoscript":
				$trigger = "typoscript";
			break;
			case "Vb":
				$trigger = "vbnet";
			break;
			case "Xml":
				$trigger = "xml";
			break;
		}
		return $trigger;
	}

	/**
	 * Function to solve CSSconfiguration which overwrites TS configuration
	 *
	 * @return	string  space and semicolon seperated CSS classes
	 */
	public function getCssConfig() {
		$string = '';
		if (is_array($this->values['css'])) {
			// built brushes string
			$string = '; ';
			foreach($this->values['css'] AS $config => $configValue) {
				// use TS config or not available in SyntaxHighlighter v3
				if (($configValue != "" && $configValue != "auto") && $config != "toolbar") {
					// highlight range
					if ($config == "highlight") {
						$string .= " highlight: [".t3lib_div::expandList($configValue)."]; ";
					} else {
						$state = ($configValue) ? "true" : "false";
						$string .= $config . ": " . $state . "; ";
					}
				}
			}
			$string = substr($string, 0, -2);
		}
		return $string;
	}

	/**
	 * Function to solve default strings
	 *
	 * @return	string  javascript cofiguration
	 */
	public function getDefaults() {
		$temp = '';
		if (is_array($this->conf['defaults.']) >0) {
			foreach($this->conf['defaults.'] AS $key => $value) {
				// not available in SyntaxHighlighter v3
				if ($key != "toolbar") {
					$temp .= "\t" . "SyntaxHighlighter.defaults['".trim($key)."'] = ".trim($value).';'."\n";
				}
			}
		}
		return $temp;
	}

	/**
	 * Function to solve language strings
	 *
	 * @return	string  javascript language configuration
	 */
	public function getLanguageStrings() {
		$temp = '';
		if (is_array($this->conf['config.']['strings.'])>0) {
			foreach($this->conf['config.']['strings.'] AS $key => $value) {
				// not available in SyntaxHighlighter v3
				if ($key != "viewSource" && $key != "copyToClipboard" && $key != "copyToClipboardConfirmation" && $key != "print") {
					$temp .= "\t" . "SyntaxHighlighter.config.strings.".trim($key).' = "'.trim($value).'";'."\n";
				}
			}
		}
		return $temp;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_standalone.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_standalone.php']);
}

?>