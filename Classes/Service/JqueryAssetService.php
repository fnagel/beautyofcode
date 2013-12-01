<?php
namespace TYPO3\Beautyofcode\Service;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery')) {
	require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery') . 'class.tx_t3jquery.php');
}

/**
 * Service which adds and generates all necessary assets for the jquery version
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class JqueryAssetService extends \TYPO3\Beautyofcode\Service\AbstractVersionAssetService {

	/**
	 *
	 * @var string
	 */
	protected $templatePathAndFilename = 'typo3conf/ext/beautyofcode/Resources/Private/Templates/Inline/Jquery.html';

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Service\AbstractVersionAssetService::load()
	 */
	public function load() {
		$this->addJavascriptLibraries();

		$this->addInlineJavascript();
	}

	/**
	 *
	 * @return void
	 */
	protected function addJavascriptLibraries() {
		$addJquery = (boolean) $this->configuration['addjQuery'];
		$addJqueryFromT3Jquery = T3JQUERY === TRUE;

		$jsLibraries = array();

		if ($addJquery && $addJqueryFromT3Jquery) {
			\tx_t3jquery::addJqJS();
		} else if ($addJquery) {
			$jsLibraries['jquery'] = 'EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery-1.3.2.min.js';
		}

		$jsLibraries['boc'] = 'EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery.beautyOfCode.js';

		foreach ($jsLibraries as $jsLibraryKey => $jsLibrary) {
			$this->pageRenderer->addJsFooterLibrary(
				'beautyofcode_' . $jsLibraryKey,
				$this->typoscriptFrontendController
					->tmpl
					->getFileName($jsLibrary)
			);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Service\AbstractLibraryService::getClassAttributeConfiguration()
	 */
	public function getClassAttributeConfiguration() {
		$configurationItems = array();

		foreach ($this->classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if ($configurationKey === 'highlight') {
				$key = $configurationKey;
				$value = sprintf('[%s]', $configurationValue);
			} else {
				$key = $configurationValue ? '' : 'no-';
				$value = $configurationKey;
			}

			$configurationItems[] = sprintf('boc-%s%s', $key, $value);
		}

		return implode(' ', $configurationItems);
	}
}
?>