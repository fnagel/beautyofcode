<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Felix Nagel (info@felixnagel.com)
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
 * Function to add select options dynamically (loaded out of TS)
 * 
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class tx_beautyofcode_addFields {

	/**
	 * This function is called from the flexform and 
	 * adds avaiable programming languages to the select options
	 *
	 * @param	array	flexform data
	 * @return	array
	 */
	public function getConfiguredLangauges ($config) {
		static $cachedFields = 0;
	
		if ($cachedFields != 0) {	
			$config['items'] = $cachedFields;
		} else {		
			$configArray = $this->getConfig($config);

			// make brushes list to flexform selectbox item array
			$optionList = array();
			if (strlen($configArray['brushes'])>0) {
				$brushesArray = explode(',', $configArray['brushes']);
				// make unique
				foreach ($brushesArray as &$value){
					$value = serialize(trim($value));
				}
				$brushesArray = array_unique($brushesArray);
				foreach ($brushesArray as &$value){
					$value = unserialize($value);
				}
				// sort a-z
				sort($brushesArray);
				// get label and css selector
				$i = 0;
				for ($x = 0; $x < count($brushesArray); $x++) { 
					// Plain is defined as default in flexform, so we dont add it again
					if (trim($brushesArray[$x]) != "Plain") {
						$optionList[$i] = $this->getFieldValues(trim($brushesArray[$x]));
						$i++;
					}
				}
			}
			$config['items'] = array_merge($config['items'],$optionList);
		}
		$cachedFields = $config['items'];
		
		return $config;
	}
	
	/**
	 * Solves the key delivered by TS to the CSS and JS key
	 *
	 * @param	string	language key
	 * @return	array
	 */
	public function getFieldValues($key) {
		$css = "";
		$label = "";
		switch ($key) {	
			case "AS3": 
				$css = "actionscript3";
				$label = "Actionscript 3";
			break;			
			case "Bash":
				$css = "bash";
				$label = "Bash / Shell";
			break;
			case "ColdFusion": 
				$css = "coldfusion";
				$label = "ColdFusion";
			break;
			case "Cpp": 
				$css = "cpp";
				$label = "C / C++";
			break;
			case "CSharp": 
				$css = "csharp";
				$label = "C#";
			break;
			case "Css": 
				$css = "css";
				$label = "CSS";
			break;
			case "Delphi": 
				$css = "delphi";
				$label = "Delphi / Pas / Pascal";
			break;
			case "Diff": 
				$css = "diff";
				$label = "Diff / Patch";
			break;
			case "Erlang": 
				$css = "erlang";
				$label = "Erlang";
			break;
			case "Groovy": 
				$css = "groovy";
				$label = "Groovy";
			break;
			case "Java": 
				$css = "java";
				$label = "Java";
			break;
			case "JavaFX": 
				$css = "javafx";
				$label = "Java FX";
			break;
			case "JScript": 
				$css = "javascript";
				$label = "Java-Script";
			break;
			case "Perl": 
				$css = "perl";
				$label = "Perl";
			break;
			case "Php": 
				$css = "php";
				$label = "PHP";
			break;
			case "PowerShell": 
				$css = "powershell";
				$label = "Power-Shell";
			break;
			case "Python": 
				$css = "python";
				$label = "Python";
			break;
			case "Ruby": 
				$css = "ruby";
				$label = "Ruby on Rails";
			break;
			case "Scala": 
				$css = "scala";
				$label = "Scala";
			break;
			case "Sql": 
				$css = "sql";
				$label = "SQL / MySQL";
			break;
			case "Typoscript": 
				$css = "typoscript";
				$label = "Typoscript";
			break;
			case "Vb": 
				$css = "vbnet";
				$label = "Virtual Basic / .Net";
			break;
			case "Xml": 
				$css = "xml";
				$label = "XML / XSLT / XHTML / HTML";
			break;
		}				
		return array(0 => $label, 1 => $css);
	}
	
	/**
	 * Generates TS Config pf the plugin
	 *
	 * @param	array	config
	 * @return	array
	 */
	public function getConfig($config) {

		// import t3lib_page class if not already done (this should be a problem of TYPO3 4.2.x only)
		if (!class_exists('t3lib_pageSelect', FALSE)) {
			t3lib_div::requireOnce(PATH_t3lib."class.t3lib_page.php");   
		}
		
		// Initialize the page selector
		$sysPage = t3lib_div::makeInstance('t3lib_pageSelect');
		$sysPage->init(true);

		// Initialize the TS template
		$template = t3lib_div::makeInstance('t3lib_TStemplate');
		$template->init();

		// Avoid an error
		$template->tt_track = 0;

		// Get rootline for current PID
		$rootline = $sysPage->getRootLine($config["row"]["pid"]);

		// Start TS template
		$template->start($rootline);

		// Generate TS config
		$template->generateConfig();
		
		return $template->setup['plugin.']['tx_beautyofcode_pi1.'];	
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_addFields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_addFields.php']);
}
?>