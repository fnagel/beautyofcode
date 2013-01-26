<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Alexey Gafiulov <gafiulov@i-tribe.com>
*      2010 Andrey Krotov <krotov@i-tribe.com>
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
 * Add highlight of ts field in flexform
 *
 * This file is taken from the extension typoscriptcode
 * modyfied by Felix Nagel for beautyofcode 
 */


class tx_beautyofcode_TCAform {

	/**
	 * params to generate field
	 *
	 * @var array
	 */
	protected $_parameters = array(
		'numberOfRows' => 30,
		'numberOfCols' => 100,
		'defaultExtras' => '',
	);

	/**
	 *
	 * @var tx_t3editor
	 */
	protected $_t3editor = NULL;
	
	/**
	 *
	 * @var string
	 */
	protected $_t3editor_version = 'x.x';
	
	/**
	 * get version of t3editor ("0.x" = typo3 4.2, "1.0" = typo3 4.3, "1.1" = typo3 4.4, "1.5" = typo3 4.5, "x.x" = others)
	 *
	 */
	protected function _getT3editorVersion() {
		$_EXTKEY = 't3editor';
		include(t3lib_extMgm::extPath($_EXTKEY) . 'ext_emconf.php');

		$ver = explode('.', $EM_CONF[$_EXTKEY]['version']);
		if ($ver[0] === '0') {
			$this->_t3editor_version = '0.x';
		} else if ($ver[0] === '1') {
			if ($ver[1] === '0') {
				$this->_t3editor_version = '1.0';
			} else if ($ver[1] === '1') {
				$this->_t3editor_version = '1.1';
			} else if ($ver[1] === '5') {
				$this->_t3editor_version = '1.5';
			}
		}
	}

	
	/**
	 * construct t3editor and check version
	 *
	 */
	protected function _initT3editor() {
		$this->_getT3editorVersion();

		switch ($this->_t3editor_version) {
			case '0.x':
			case '1.0':
				require_once(t3lib_extMgm::extPath('t3editor', 'class.tx_t3editor.php'));
				$this->_t3editor = t3lib_div::makeInstance('tx_t3editor');
				break;
			case '1.1':
			case '1.5':
			default:
				require_once(t3lib_extMgm::extPath('t3editor', 'classes/class.tx_t3editor.php'));
				$this->_t3editor = t3lib_div::makeInstance('tx_t3editor');
				$this->_t3editor->setMode(tx_t3editor::MODE_TYPOSCRIPT);
				break;
		}
	}

	/**
	 * build editor area html
	 *
	 * @param SC_alt_doc $pObj
	 * @param string $name
	 * @param string $content
	 * @return string
	 */
	protected function _buildT3EditorCode(&$pObj, $name, $content) {
		// create t3editor
		$this->_initT3editor();

		// check if editor is enabled
		switch ($this->_t3editor_version) {
			case '0.x':
			case '1.0':
				if (!$this->_t3editor->isEnabled) return;
				break;
			case '1.1':
			case '1.5':
			default:
				if (!$this->_t3editor->isEnabled()) return;
				break;
		}

		// workaround to make editor work on hidden tabs.
		// force editor to be disabled by default and show when tab will be active
		$editor_enabled = $GLOBALS['BE_USER']->uc['disableT3Editor'] ? 0 : 1;
		$orig_disableT3Editor = $GLOBALS['BE_USER']->uc['disableT3Editor'];
		$GLOBALS['BE_USER']->uc['disableT3Editor'] = 1;
		
		// add headers
		$t3editor_js = $this->_t3editor->getJavascriptCode($pObj->doc);
		switch ($this->_t3editor_version) {
			case '0.x':
			case '1.0':
			case '1.1':
				break;
			case '1.5':
			default:
				$t3editor_js = str_replace(
					'"sysext/t3editor/res/jslib/parse_typoscript/tokenizetyposcript.js"',
					'"../../../sysext/t3editor/res/jslib/parse_typoscript/tokenizetyposcript.js"',
					$t3editor_js);
				$t3editor_js = str_replace(
					'"sysext/t3editor/res/jslib/parse_typoscript/parsetyposcript.js"',
					'"../../../sysext/t3editor/res/jslib/parse_typoscript/parsetyposcript.js"',
					$t3editor_js);
				break;
		}
		$pObj->doc->JScode .= $t3editor_js;
		$pObj->doc->loadJavascriptLib(t3lib_extMgm::extRelPath('beautyofcode') . 'lib/tx_tstemplateinfo.js');

		// generate editor code
		$outCode = $this->_t3editor->getCodeEditor(
			$name,
			$this->_parameters['defaultExtras'],
			$content,
			'rows="'.$this->_parameters['numberOfRows'].'" '.'wrap="off" '.$pObj->doc->formWidthText($this->_parameters['numberOfCols'], 'width:90%;height:60%', 'off'),
			'',
			array(
				'pageId' => intval($pObj->viewId)
			)
		);

		if ($editor_enabled) {
			
			// delayed activation of t3editor - check for visitibility 10 times per second and activate once textarea became visible
			switch ($this->_t3editor_version) {
				case '0.x':
				case '1.0':
					$toggleFunction = 't3editor_toggleEditor(t3editor_delayed_checkbox);';
					$suppressErrors = '';
					break;
				case '1.1':
					$toggleFunction = 'T3editor.toggleEditor(t3editor_delayed_checkbox);';
					$suppressErrors = '';
					break;
				case '1.5':
				default:
					$toggleFunction = 'T3editor.toggleEditor(t3editor_delayed_checkbox);';
					$suppressErrors = '
						function t3editor_suppressError() {
							return true;
						}
						window.onerror = t3editor_suppressError;
						';
					break;
			}


			$outCode .= '
				<script type="text/javascript">
					var t3editor_delayed_interval = 0;
					var t3editor_delayed_textarea;
					var t3editor_delayed_checkbox;
					
					Event.observe(window, "load",
						function() {
							t3editor_delayed_textarea = $("t3editor_1");
							t3editor_delayed_checkbox = $("t3editor_disableEditor_1_checkbox");
							t3editor_delayed_interval = setInterval("check_t3editor_interval()", 100);
						}
					);

					function check_t3editor_interval() {
						var textareaDim = $(t3editor_delayed_textarea).getDimensions();
						if (textareaDim.width != 0) {
							clearInterval(t3editor_delayed_interval);
							t3editor_delayed_checkbox.checked = false;
							'.$toggleFunction.'
						} 
					}
					'.$suppressErrors.'
				</script>
			';
		}

		// restore forced setting
		$GLOBALS['BE_USER']->uc['disableT3Editor'] = $orig_disableT3Editor;

		return $outCode;
	}

	/**
	 * userfunc to call from flexform and init t3editor field or textarea with tab support
	 *
	 * @param array $PA
	 * @param t3lib_TCEforms $fobj
	 * @return string
	 */
	public function drawCodeText($PA, &$fobj) {
		$this->_parameters['defaultExtras'] = $PA['fieldConf']['defaultExtras'];

		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode']);

		$outCode = '';

		if ($extensionConfiguration['enable_t3editor'] && t3lib_extMgm::isLoaded('t3editor')) {
			$outCode = $this->_buildT3EditorCode($GLOBALS['SOBE'], $PA['itemFormElName'], htmlspecialchars($PA['itemFormElValue']));
		}

		if ($outCode == '') {
			$outCode = '<textarea
				name="'.$PA['itemFormElName'].'"
				rows="'.$this->_parameters['numberOfRows'].'"
				cols="'.$this->_parameters['numberOfCols'].'"
				wrap="off"
				style="width: 90%; height: 60%;"
				class="' . $this->_parameters['defaultExtras'] .'"
				onchange="'.htmlspecialchars(implode('', $PA['fieldChangeFunc'])).'"'.$PA['onFocus'].'>'.t3lib_div::formatForTextarea($PA['itemFormElValue']).'</textarea>';
		}
		
		return $outCode;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_TCAform.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_TCAform.php']);
}
?>