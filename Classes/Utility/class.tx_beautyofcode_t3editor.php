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

/**
 * Add t3editor in flexform
 *
 * This file was developed and tested with TYPO3 4.7.7 and its t3editor extension
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */

class tx_beautyofcode_tceforms_wizard {

	public function main($parameters, $pObj) {
		// check if t3editor should be loaded at all
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode']);
		if ($extensionConfiguration['enable_t3editor'] && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3editor')) {
			\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3editor', 'classes/class.tx_t3editor.php'));
			$t3editor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_t3editor');
		} else {
			return;
		}

		if (!$t3editor->isEnabled()) {
			return;
		}

		// get flexform content
		$flexform = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($parameters['row'][$parameters['field']]);
		$content = $flexform['data']['sDEF']['lDEF']['cCode']['vDEF'];
		$language = $flexform['data']['sDEF']['lDEF']['cLang']['vDEF'];

		// set code type
		// TODO: check if more available at sysext\t3editor\classes\class.tx_t3editor.php
		switch($language){
			case 'php':
				$t3editor->setMode(tx_t3editor::MODE_PHP);
				break;
			case 'xml':
				$t3editor->setMode(tx_t3editor::MODE_XML);
				break;
			case 'javascript':
				$t3editor->setMode(tx_t3editor::MODE_JAVASCRIPT);
				break;
			case 'css':
				$t3editor->setMode(tx_t3editor::MODE_CSS);
				break;
			case 'typoscript':
				$t3editor->setMode(tx_t3editor::MODE_TYPOSCRIPT);
				break;
			default:
				$t3editor->setMode(tx_t3editor::MODE_MIXED);
		}

		$config = $GLOBALS['TCA'][$parameters['table']]['columns'][$parameters['field']]['config'];
		$doc = $GLOBALS['SOBE']->doc;

		$attributes =
			'rows="' . $config['rows'] . '" ' .
			'cols="' . $config['cols'] . '" ' .
			'wrap="off" ' .
			'style="width:98%; height: 100%" ' .
			'onchange="' . $parameters['fieldChangeFunc']['TBE_EDITOR_fieldChanged'] . '" ';

		$parameters['item'] = '';
		$parameters['item'] .= $t3editor->getCodeEditor(
			$parameters['itemName'],
			'fixed-font enable-tab',
			$content,
			$attributes,
			$parameters['table'] . ' > ' . $parameters['field'],
			array(
				'target' => intval($pObj->target)
			)
		);
		$parameters['item'] .= $t3editor->getJavascriptCode($doc);

		return '';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_t3editor.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_t3editor.php']);
}
?>