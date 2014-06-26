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
use TYPO3\Beautyofcode\Configuration\Exception\UnableToLoadT3EditorException;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Add t3editor in flexform
 *
 * This file was developed and tested with TYPO3 6.2 and its t3editor extension
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
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 * TYPO3_CONF_VARS.EXT.extConf.beautyofcode
	 *
	 * @var array
	 */
	protected $extensionConfiguration = array();

	/**
	 *
	 * @var \TYPO3\CMS\T3editor\T3editor
	 */
	protected $t3editor;

	/**
	 * initalize
	 *
	 * @return void
	 */
	public function initialize() {
		$this->backendDocumentTemplate = $GLOBALS['SOBE']->doc;

		$this->initializeExtensionConfiguration();

		$this->initializeFlexformConfiguration();

		$this->initializeT3Editor();

		$this->initializeT3EditorMode();
	}


	/**
	 * initializeExtensionConfiguration
	 *
	 * @return void
	 */
	protected function initializeExtensionConfiguration() {
		try {
			$extensionConfiguration = ArrayUtility::getValueByPath(
				$GLOBALS,
				'TYPO3_CONF_VARS/EXT/extConf/beautyofcode'
			);
			$this->extensionConfiguration = unserialize($extensionConfiguration);
		} catch (\RuntimeException $e) {
			$this->extensionConfiguration = array();
		}
	}

	/**
	 * initializeFlexformConfiguration
	 *
	 * @return void
	 */
	protected function initializeFlexformConfiguration() {
		$row = $this->parameters['row'];
		$field = $this->parameters['field'];

		if ('' !== trim($row[$field])) {
			$this->flexformData = GeneralUtility::xml2array($row[$field]);
		}
	}


	/**
	 * initializes the t3editor
	 *
	 * @return void
	 * @throws UnableToLoadT3EditorException
	 */
	protected function initializeT3Editor() {
		$isExtensionLoaded = ExtensionManagementUtility::isLoaded('t3editor');
		$isEnabled = (boolean) $this->extensionConfiguration['enable_t3editor'];

		if (!$isExtensionLoaded) {
			throw new UnableToLoadT3EditorException(
				'Cannot instantiate T3editor: ext:t3editor not installed.',
				1403806638
			);
		}

		if (!$isEnabled) {
			throw new UnableToLoadT3EditorException(
				'Cannot instantiate T3editor: Feature disabled.',
				1403806644
			);
		}

		$this->t3editor = GeneralUtility::makeInstance(
			'TYPO3\\CMS\\T3editor\\T3editor'
		);

		if (!$this->t3editor->isEnabled()) {
			throw new UnableToLoadT3EditorException(
				'Cannot instantiate T3editor: Feature internally disabled.',
				1403806649
			);
		}
	}

	/**
	 * sets the language mode of the T3Editor
	 *
	 * @return void
	 * @todo: check if more available at sysext\t3editor\
	 */
	protected function initializeT3EditorMode() {
		try {
			$language = ArrayUtility::getValueByPath(
				$this->flexformData,
				'data/sDEF/lDEF/cLang/vDEF'
			);

			$modeConstant = sprintf(
				'TYPO3\\CMS\\T3editor\\T3editor::MODE_%s',
				strtoupper($language)
			);

			$mode = \TYPO3\CMS\T3editor\T3editor::MODE_MIXED;

			if (defined($modeConstant)) {
				$mode = constant($modeConstant);
			}
		} catch (\RuntimeException $e) {
			$mode = \TYPO3\CMS\T3editor\T3editor::MODE_MIXED;
		}

		$this->t3editor->setMode($mode);
	}

	/**
	 * Renders a T3editor instance and applies all necessary stuff for highlighting
	 *
	 * @param array &$parameters Array of userFunc arguments
	 * @param \TYPO3\CMS\Backend\Form\FormEngine &$formEngine
	 * @return void|string
	 */
	public function main(
		&$parameters,
		\TYPO3\CMS\Backend\Form\FormEngine &$formEngine
	) {
		try {
			$this->parameters = $parameters;
			$this->formEngine = $formEngine;

			$this->initialize();

			$content = ArrayUtility::getValueByPath(
				$this->flexformData,
				'data/sDEF/lDEF/cCode/vDEF'
			);
		} catch (UnableToLoadT3EditorException $e) {
			return;
		} catch (\RuntimeException $e) {
			$content = '';
		}

		$itemMarkup = $this->getT3EditorMarkup($content);
		$itemMarkup .= $this->getDimensionsPatchMarkup();

		$this->parameters['item'] = $itemMarkup;

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

		try {
			$onChange = ArrayUtility::getValueByPath(
				$this->parameters,
				'fieldChangeFunc/TBE_EDITOR_fieldChanged'
			);
		} catch (\RuntimeException $e) {
			$onChange = 'javascript:;';
		}

		return sprintf(
			'rows="%" cols="%s" wrap="%s" style="%s" onchange="%s" ',
			$fieldConfig['rows'],
			$fieldConfig['cols'],
			'off',
			'width: 98%; height: 100%',
			$onChange
		);
	}

	/**
	 * getFieldConfig
	 *
	 * @return array $fieldConfig TCA/flexform field configuration
	 */
	protected function getFieldConfig() {
		if (is_array($this->parameters['fieldConfig'])) {
			return $this->parameters['fieldConfig'];
		}

		try {
			$path = sprintf(
				'%s/columns/%s/config',
				$this->parameters['table'],
				$this->parameters['field']
			);
			$fieldConfig = ArrayUtility::getValueByPath(
				$GLOBALS['TCA'],
				$path
			);
		} catch (\RuntimeException $e) {
			$fieldConfig = array(
				'rows' => 40,
				'cols' => 10
			);
		}

		return $fieldConfig;
	}

	/**
	 * getDimensionsPatchMarkup
	 *
	 * @return string
	 */
	protected function getDimensionsPatchMarkup() {
		$file = GeneralUtility::getFileAbsFileName(
			'EXT:beautyofcode/Resources/Public/Javascript/T3editorDimensions.js'
		);
		$file = '/' . PathUtility::stripPathSitePrefix($file);

		return sprintf(
			'<script type="text/javascript" src="%s"></script>',
			$file
		);
	}
}
?>