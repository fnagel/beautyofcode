<?php
namespace TYPO3\Beautyofcode\Configuration\Flexform;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Add t3editor in flexform
 *
 * This file was developed and tested with TYPO3 4.7.7 and its t3editor extension
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package	\TYPO3\Beautyofcode\Configuration\Flexform
 */
class T3EditorWizard {

	/**
	 * Array of userFunc arguments, each passed by reference
	 *
	 *   item - Rendered HTML markup for the field item so far
	 *   icon - Wizard icon HTML markup, item title if not configured
	 *   iTitle - Item title
	 *   wConf - Wizard configuration
	 *   row - Reference to the database record where the
	 *         wizard is configured for a specific field
	 *
	 * @var array
	 */
	protected $parameters;

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Form\FormEngine
	 */
	protected $formEngine;

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
	 */
	protected $backendDocumentTemplate;

	/**
	 *
	 * @var \TYPO3\CMS\T3editor\T3editor
	 */
	protected $t3editor;

	/**
	 *
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 * initalize
	 *
	 * @return void
	 */
	public function initialize() {
		$this->backendDocumentTemplate = $GLOBALS['SOBE']->doc;

		// get flexform content
		if ('' !== trim($this->parameters['row'][$this->parameters['field']])) {
			$this->flexformData = GeneralUtility::xml2array(
				$this->parameters['row'][$this->parameters['field']]
			);
		}

		$this->initializeT3Editor();

		$this->initializeT3EditorMode();
	}

	/**
	 * initializes the t3editor
	 *
	 * @return void
	 * @throws \TYPO3\Beautyofcode\Configuration\Exception\UnableToLoadT3EditorException
	 */
	protected function initializeT3Editor() {
		// check if t3editor should be loaded at all
		$extensionConfiguration = unserialize(
			$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode']
		);

		$enableT3Editor = (boolean) $extensionConfiguration['enable_t3editor'];
		$t3EditorLoaded = ExtensionManagementUtility::isLoaded('t3editor');

		if ($enableT3Editor && $t3EditorLoaded) {
			$t3EditorClass = ExtensionManagementUtility::extPath(
				't3editor',
				'Classes/T3editor.php'
			);

			GeneralUtility::requireOnce($t3EditorClass);

			$this->t3editor = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\T3editor\\T3editor'
			);
		}

		if (is_null($this->t3editor) || !$this->t3editor->isEnabled()) {
			throw new \TYPO3\Beautyofcode\Configuration\Exception\UnableToLoadT3EditorException(
				'Cannot instantiate T3editor or feature disabled.',
				1403645949
			);
		}
	}

	/**
	 * sets the language mode of the T3Editor
	 *
	 * @return void
	 */
	protected function initializeT3EditorMode() {
		try {
			$language = ArrayUtility::getValueByPath(
				$this->flexformData,
				'data/sDEF/lDEF/cLang/vDEF'
			);

			// set code type
			// TODO: check if more available at sysext\t3editor\classes\class.tx_t3editor.php
			$modeConstant = 'TYPO3\\CMS\\T3editor\\T3editor::MODE_' . strtoupper($language);
			if (defined($modeConstant)) {
				$mode = constant($modeConstant);
			} else {
				$mode = \TYPO3\CMS\T3editor\T3editor::MODE_MIXED;
			}
		} catch (\RuntimeException $e) {
			$mode = \TYPO3\CMS\T3editor\T3editor::MODE_MIXED;
		}

		$this->t3editor->setMode($mode);
	}

	/**
	 * renders a t3editor instance and applies all necessary stuff for highlighting
	 *
	 * @param array &$parameters Array of userFunc arguments
	 * @param \TYPO3\CMS\Backend\Form\FormEngine &$pObj
	 * @return void|string
	 */
	public function main(&$parameters, \TYPO3\CMS\Backend\Form\FormEngine &$formEngine) {
		try {
			$this->parameters = $parameters;
			$this->formEngine = $formEngine;

			$this->initialize();

			$content = ArrayUtility::getValueByPath(
				$this->flexformData,
				'data/sDEF/lDEF/cCode/vDEF'
			);
		} catch (\TYPO3\Beautyofcode\Configuration\Exception\UnableToLoadT3EditorException $e) {
			return;
		} catch (\RuntimeException $e) {
			$content = '';
		}

		$this->parameters['item'] = '';
		$this->parameters['item'] .= $this->getT3EditorMarkup($content);
		$this->parameters['item'] .= '<script type="text/javascript" src="/typo3conf/ext/beautyofcode/Resources/Public/Javascript/T3editorDimensions.js"></script>';

		return '';
	}

	/**
	 * Builds and returns T3Editor markup for the given $content
	 *
	 * @param string $content
	 * @return string
	 */
	protected function getT3EditorMarkup($content) {
		$textareaAttributes = $this->getTextareaAttributes();
		$statusBarTitle = sprintf(
			'%s > %s',
			$this->parameters['table'],
			$this->parameters['field']
		);
		$hiddenFields = array(
			'target' => intval($this->formEngine->target)
		);

		$html = $this->t3editor->getCodeEditor(
			$this->parameters['itemName'],
			'fixed-font enable-tab',
			$content,
			$textareaAttributes,
			$statusBarTitle,
			$hiddenFields
		);

		$html .= $this->t3editor->getJavascriptCode(
			$this->backendDocumentTemplate
		);

		return $html;
	}

	/**
	 * returns a string of additional textarea attributes
	 *
	 * @return string
	 */
	protected function getTextareaAttributes() {
		$fieldConfig = $this->getFieldConfig();
		$onChangeFunction = $this->parameters['fieldChangeFunc']['TBE_EDITOR_fieldChanged'];

		return sprintf(
			'rows="%" cols="%s" wrap="%s" style="%s" onchange="%s" ',
			$fieldConfig['rows'],
			$fieldConfig['cols'],
			'off',
			'width:98%; height: 100%',
			$onChangeFunction
		);
	}

	/**
	 *
	 * @return array $fieldConfig TCA/flexform field configuration
	 */
	protected function getFieldConfig() {
		if (is_array($this->parameters['fieldConfig'])) {
			$fieldConfig = $this->parameters['fieldConfig'];
		} else {
			$fieldConfig = $GLOBALS['TCA'][$this->parameters['table']]['columns'][$this->parameters['field']]['config'];
		}

		return $fieldConfig;
	}
}
?>