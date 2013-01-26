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
class tx_beautyofcode_cms_layout {
	/**
	* Returns information about this extension's pi1 plugin
	*
	* @param	array		$params	Parameters to the hook
	* @param	object		$pObj	A reference to calling object
	* @return	string		Information about pi1 plugin
	*/
	function getExtensionSummary($params, &$pObj) {
		if ($params['row']['list_type'] == 'beautyofcode_pi1') {
			$data = t3lib_div::xml2array($params['row']['pi_flexform']);
			$uid = $params['row']['uid'];
			if (is_array($data)) {
				$code = $data['data']['sDEF']['lDEF']['cCode']['vDEF'];
				if (strlen(trim($data['data']['sDEF']['lDEF']['cLabel']['vDEF'])) > 0) {
					$result = "<strong>" . htmlspecialchars($data['data']['sDEF']['lDEF']['cLabel']['vDEF']) . "</strong>";
				} else {
					$result = "<em>" . $GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/pi1/locallang_db.xml:cms_layout.no_label') . "</em>";
				}
				$result .= "<br /><br /><strong>" . $GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/pi1/locallang_db.xml:code') . "</strong> (" . htmlspecialchars($data['data']['sDEF']['lDEF']['cLang']['vDEF']) . ")<br />";
				if (strlen($code)>0) {
					// calculate height
					$proxyLines = sizeof(preg_split("/(\n)/", $code));
					$taHeight = ($proxyLines >= 15) ? "150px" : ($proxyLines * 20 + 5) . "px";
					// make textarea with code
					$result .= '<textarea id="ta_hidden' . $uid . '" style="display: none;" readonly="readonly">' . t3lib_div::formatForTextarea($code) . "</textarea>";
					$result .= '<textarea id="ta' . $uid . '" style="height: ' . $taHeight . '; width: 100%; cursor: pointer;" wrap="off" readonly="readonly"></textarea>';
					$result .= 	'<script type="text/javascript">' .
								'var ta_hidden' . $params['row']['uid'] . ' = document.getElementById("ta_hidden' . $uid . '");' .
								'var ta' . $uid . ' = document.getElementById("ta' . $uid . '");' .
								'window.setTimeout(function() { ta' . $uid . '.value = ta_hidden' . $uid . '.value; }, 500);' .
								'</script>';
				} else {
					$result .= "<em>" . $GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/pi1/locallang_db.xml:cms_layout.no_code') . "</em>";
				}
			}
		}
		return $result;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1_cms_layout.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/pi1/class.tx_beautyofcode_pi1_cms_layout.php']);
}
?>