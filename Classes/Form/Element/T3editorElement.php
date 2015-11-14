<?php
namespace TYPO3\Beautyofcode\Form\Element;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * t3editor FormEngine widget
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Form
 */
class T3editorElement extends \TYPO3\CMS\T3editor\Form\Element\T3editorElement {

	/**
	 * Map fails safe brush alias to t3editor mode keys
	 *
	 * @var array
	 */
	protected $beautyOfCodeT3editorBrushAliasMap = array(
		'markup' => self::MODE_XML,
	);

	/**
	 * @inheritdoc
	 * @return void
	 */
	public function setMode($mode) {
		$mode = $this->setModeDynamic($mode);

		parent::setMode($mode);
	}

	/**
	 * Dynamic update of the t3editor format
	 *
	 * @param string $mode Expects one of the predefined constants
	 *
	 * @return string
	 */
	protected function setModeDynamic($mode) {
		if (!$this->isBeautyOfCodeElement()) {
			return $mode;
		}

		// Get current flexform language value
		$flexformLanguageKey = current($this->data['databaseRow']['pi_flexform']['data']['sDEF']['lDEF']['cLang']['vDEF']);

		if (array_search($flexformLanguageKey, $this->allowedModes) !== FALSE) {
			return $flexformLanguageKey;
		}

		if (array_key_exists($flexformLanguageKey, $this->beautyOfCodeT3editorBrushAliasMap)) {
			return $this->beautyOfCodeT3editorBrushAliasMap[$flexformLanguageKey];
		}

		return $mode;
	}

	/**
	 * @return boolean
	 */
	protected function isBeautyOfCodeElement() {
		return (
			$this->data['tableName'] === 'tt_content' &&
			current($this->data['databaseRow']['CType']) === 'beautyofcode_contentrenderer'
		);
	}
}
