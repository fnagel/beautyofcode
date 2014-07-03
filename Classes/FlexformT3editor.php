<?php
namespace TYPO3\Beautyofcode;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

/**
 * FlexformT3editor
 *
 * A wrapper class arount \TYPO3\CMS\T3editor\T3editor, adding a sophisticated
 * approach for setting necessary data for rendering the T3editor within
 * flexforms.
 *
 * @package \TYPO3\Beautyofcode
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FlexformT3editor extends \TYPO3\CMS\T3editor\T3editor {

	/**
	 *
	 * @var string
	 */
	const TEXTAREA_CLASSES = 'fixed-font enable-tab';

	/**
	 *
	 * @var integer
	 */
	const TEXTAREA_CSS_HEIGHT_FACTOR = 19;

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
	 */
	protected $backendDocumentTemplate;

	/**
	 *
	 * @var string
	 */
	protected $textareaFieldName;

	/**
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 *
	 * @var string
	 */
	protected $fieldName;

	/**
	 *
	 * @var array
	 */
	protected $flexformFieldConfiguration;

	/**
	 * Contains the flexform XML as associative array
	 *
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 *
	 * @var string
	 */
	protected $textareaContent = '';

	/**
	 *
	 * @var array
	 */
	protected $hiddenFields = array();

	/**
	 *
	 * @var string
	 */
	protected $textareaOnChangeFunction = 'javascript:;';

	/**
	 * setBackendDocumentTemplate
	 *
	 * @param \TYPO3\CMS\Backend\Template\DocumentTemplate $backendDocumentTemplate
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setBackendDocumentTemplate(\TYPO3\CMS\Backend\Template\DocumentTemplate $backendDocumentTemplate) {
		$this->backendDocumentTemplate = $backendDocumentTemplate;

		return $this;
	}

	/**
	 * setTextareaFieldName
	 *
	 * @param string $textareaFieldName
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setTextareaFieldName($textareaFieldName) {
		$this->textareaFieldName = $textareaFieldName;

		return $this;
	}

	/**
	 * setTableName
	 *
	 * @param string $tableName
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setTableName($tableName) {
		$this->tableName = $tableName;

		return $this;
	}

	/**
	 * setFieldName
	 *
	 * @param string $fieldName
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setFieldName($fieldName) {
		$this->fieldName = $fieldName;

		return $this;
	}

	/**
	 * setFlexformFieldConfiguration
	 *
	 * @param array $flexformFieldConfiguration
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setFlexformFieldConfiguration($flexformFieldConfiguration) {
		$this->flexformFieldConfiguration = (array) $flexformFieldConfiguration;

		return $this;
	}

	/**
	 * setFlexformData
	 *
	 * @param array $flexformData
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setFlexformData($flexformData = array()) {
		$this->flexformData = $flexformData;

		return $this;
	}

	/**
	 * determineHighlightingModeFromFlexformPath
	 *
	 * @param string $path E.g. 'data/sDEF/lDEF/cLang/vDEF'
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function determineHighlightingModeFromFlexformPath($path) {
		try {
			$language = ArrayUtility::getValueByPath(
				$this->flexformData,
				$path
			);
		} catch (\RuntimeException $e) {
			$language = 'mixed';
		}

		$modeConstant = sprintf(
			'TYPO3\\CMS\\T3editor\\T3editor::MODE_%s',
			strtoupper($language)
		);

		$mode = \TYPO3\CMS\T3editor\T3editor::MODE_MIXED;

		if (defined($modeConstant)) {
			$mode = constant($modeConstant);
		}

		$this->setMode($mode);

		return $this;
	}

	/**
	 * setTextareaContentFromFlexformPath
	 *
	 * @param string $path E.g. 'data/sDEF/lDEF/cCode/vDEF'
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setTextareaContentFromFlexformPath($path) {
		try {
			$this->textareaContent = ArrayUtility::getValueByPath(
				$this->flexformData,
				$path
			);
		} catch (\RuntimeException $e) {
			$this->textareaContent = '';
		}

		return $this;
	}

	/**
	 * addHiddenField
	 *
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function addHiddenField($fieldName, $fieldValue) {
		$this->hiddenFields[$fieldName] = $fieldValue;

		return $this;
	}

	/**
	 * setTextareaOnChangeFunction
	 *
	 * @param string $textareaOnChangeFunction
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setTextareaOnChangeFunction($textareaOnChangeFunction = '') {
		$this->textareaOnChangeFunction = $textareaOnChangeFunction;

		return $this;
	}

	/**
	 * render
	 *
	 * @return string
	 */
	public function render() {
		$textareaAttributes = $this->getTextareaAttributes();
		$statusBarTitle = $this->getStatusBar();

		$html = $this->getCodeEditor(
			$this->textareaFieldName,
			self::TEXTAREA_CLASSES,
			$this->textareaContent,
			$textareaAttributes,
			$statusBarTitle,
			$this->hiddenFields
		);

		$html .= $this->getJavascriptCode(
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
		$dimensions = $this->getTextareaDimensions();

		$height = ceil($dimensions['rows']) * self::TEXTAREA_CSS_HEIGHT_FACTOR;

		return sprintf(
			'cols="%s" rows="%s" wrap="%s" style="%s" onchange="%s" ',
			$dimensions['cols'],
			$dimensions['rows'],
			'off',
			'width: 97%; height: ' . $height . 'px',
			$this->textareaOnChangeFunction
		);
	}

	/**
	 * getTextareaDimensions
	 *
	 * @return array $fieldConfig TCA/flexform textarea field dimensions rows/cols
	 */
	protected function getTextareaDimensions() {
		try {
			$path = sprintf(
				'%s/columns/%s/config',
				$this->tableName,
				$this->fieldName
			);
			$dimensions = ArrayUtility::getValueByPath(
				$GLOBALS['TCA'],
				$path
			);
		} catch (\RuntimeException $e) {
			$dimensions = array('rows' => 40, 'cols' => 10);
		}

		ArrayUtility::mergeRecursiveWithOverrule(
			$dimensions,
			$this->flexformFieldConfiguration
		);

		return $dimensions;
	}

	/**
	 * getStatusBar
	 *
	 * @param string $format
	 * @return string
	 */
	protected function getStatusBar($format = '%s > %s') {
		return sprintf($format, $this->tableName, $this->fieldName);
	}
}
?>