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
 * @package \TYPO3\Beautyofcode
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FlexformT3editor extends \TYPO3\CMS\T3editor\T3editor {

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
	 */
	protected $backendDocumentTemplate;

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
	 * setFlexformDataFromXml
	 *
	 * @param string $xml
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function setFlexformDataFromXml($xml) {
		$this->flexformData = GeneralUtility::xml2array($xml);

		return $this;
	}

	/**
	 * determineHighlightingMode
	 *
	 * @return \TYPO3\Beautyofcode\FlexformT3editor
	 */
	public function determineHighlightingMode() {
		try {
			$language = ArrayUtility::getValueByPath(
				$this->flexformData,
				'data/sDEF/lDEF/cLang/vDEF'
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
	 * render
	 *
	 * @param sting $itemName
	 * @param string $content
	 * @param \TYPO3\CMS\Backend\Form\FormEngine $formEngine
	 * @return string
	 */
	public function render($itemName, $content, \TYPO3\CMS\Backend\Form\FormEngine $formEngine) {
		$textareaAttributes = $this->getTextareaAttributes();
		$statusBarTitle = $this->getStatusBar();
		$hiddenFields = array(
			'target' => intval($formEngine->target)
		);

		$html = $this->getCodeEditor(
			$itemName,
			'fixed-font enable-tab',
			$content,
			$textareaAttributes,
			$statusBarTitle,
			$hiddenFields
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
			$dimensions['rows'],
			$dimensions['cols'],
			'off',
			'width: 98%; height: 100%',
			$onChange
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
			$tcaConfiguration = ArrayUtility::getValueByPath(
				$GLOBALS['TCA'],
				$path
			);
			$dimensions = ArrayUtility::mergeRecursiveWithOverrule(
				$tcaConfiguration,
				$this->flexformFieldConfiguration
			);
		} catch (\RuntimeException $e) {
			$dimensions = array('rows' => 40, 'cols' => 10);
		}

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