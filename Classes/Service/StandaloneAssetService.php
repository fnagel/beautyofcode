<?php
namespace TYPO3\Beautyofcode\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Service which adds and generates all necessary assets for the standalone version
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class StandaloneAssetService extends \TYPO3\Beautyofcode\Service\AbstractVersionAssetService {

	/**
	 *
	 * @var array
	 */
	protected $classAttributeConfigurationSkipKeys = array('toolbar');

	/**
	 *
	 * @var string
	 */
	protected $templatePathAndFilename = 'typo3conf/ext/beautyofcode/Resources/Private/Templates/Inline/Standalone.html';

	/**
	 *
	 * @var string
	 */
	protected $filePathBase = 'http://alexgorbatchev.com/';

	/**
	 *
	 * @var string
	 */
	protected $filePathScripts = 'pub/sh/current/scripts/';

	/**
	 *
	 * @var string
	 */
	protected $filePathStyles = 'pub/sh/current/styles/';

	/**
	 *
	 * @var boolean
	 */
	protected $excludeAssetFromConcatenation = TRUE;

	/**
	 * A map of $brush => $cssTag for the lazy loader
	 *
	 * @var array
	 */
	protected $brushCssClassMap = array(
		'AS3' => 'actionscript3',
		'Bash' => 'bash',
		'ColdFusion' => 'coldfusion',
		'Cpp' => 'cpp',
		'CSharp' => 'csharp',
		'Css' => 'css',
		'Delphi' => 'delphi',
		'Diff' => 'diff',
		'Erlang' => 'erlang',
		'Groovy' => 'groovy',
		'Java' => 'java',
		'JavaFX' => 'javafx',
		'JScript' => 'javascript',
		'Perl' => 'perl',
		'Php' => 'php',
		'PowerShell' => 'powershell',
		'Python' => 'python',
		'Ruby' => 'ruby',
		'Scala' => 'scala',
		'Sql' => 'sql',
		'Typoscript' => 'typoscript',
		'Vb' => 'vbnet',
		'Xml' => 'xml',
	);

	/**
	 *
	 * @var array
	 */
	protected $brushes = array();

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Service\AbstractVersionAssetService::load()
	 */
	public function load() {
		$this->checkAndPossiblyOverrideFilePaths();

		$this->addJavascriptLibaries();

		$this->addStylesheets();

		$this->loadBrushes();

		$this->addInlineJavascript(array(
			'brushes' => $this->brushes,
			'filePathBase' => $this->filePathBase,
			'filePathScripts' => $this->filePathScripts,
		));
	}

	/**
	 * Checks if the configured paths are empty, if not override
	 *
	 * @return void
	 */
	protected function checkAndPossiblyOverrideFilePaths() {
		$isBaseUrlSet = trim($this->configuration['baseUrl']) !== '';
		$isStylePathSet = trim($this->configuration['styles']) !== '';
		$isScriptPathSet = trim($this->configuration['scripts']) !== '';

		$overridePaths = $isBaseUrlSet && $isStylePathSet && $isScriptPathSet;

		if ($overridePaths) {
			$this->filePathBase = $this->bocGeneralUtility->makeAbsolutePath(
				$this->configuration['baseUrl']
			);
			$this->filePathScripts = trim($this->configuration['scripts']);
			$this->filePathStyles = trim($this->configuration['styles']);
		}

		$this->excludeAssetFromConcatenation = !\TYPO3\CMS\Core\Utility\GeneralUtility::isOnCurrentHost($this->filePathBase);
	}

	/**
	 *
	 * @return void
	 */
	protected function addJavascriptLibaries() {
		$this->pageRenderer->addJsFooterLibrary(
			'beautyofcode_JS_shCoreJS',
			$this->filePathBase . $this->filePathScripts . 'shCore.js',
			'text/javascript',
			TRUE,
			FALSE,
			'',
			$this->excludeAssetFromConcatenation
		);

		$this->pageRenderer->addJsFooterLibrary(
			'beautyofcode_JS_shAutoloader',
			$this->filePathBase . $this->filePathScripts . 'shAutoloader.js',
			'text/javascript',
			TRUE,
			FALSE,
			'',
			$this->excludeAssetFromConcatenation
		);
	}

	/**
	 *
	 * @return void
	 */
	protected function addStylesheets() {
		$cssStyleFile = 'shCoreDefault.css';

		if ('' !== trim($this->configuration['theme'])) {
			$cssStyleFile = 'shTheme' . trim($this->configuration['theme']) . '.css';
		}

		$this->pageRenderer->addCssFile(
			$this->filePathBase . $this->filePathStyles . 'shCore.css',
			'stylesheet',
			'all',
			'',
			$this->excludeAssetFromConcatenation,
			FALSE,
			'',
			$this->excludeAssetFromConcatenation
		);

		$this->pageRenderer->addCssFile(
			$this->filePathBase . $this->filePathStyles . $cssStyleFile,
			'stylesheet',
			'all',
			'',
			$this->excludeAssetFromConcatenation,
			FALSE,
			'',
			$this->excludeAssetFromConcatenation
		);
	}

	/**
	 * Iterates over configured brushes and looks up the matching css tag
	 *
	 * @return void
	 */
	protected function loadBrushes() {
		$brushes = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
			',',
			$this->configuration['brushes']
		);

		foreach ($brushes as $brush) {
			$this->brushes[$this->brushCssClassMap[$brush]] = $brush;
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
				$value = sprintf('[%s]', $value);
			} else {
				$value = var_export((boolean) $configurationValue, TRUE);
			}

			$configurationItems[] = sprintf('%s: %s', $configurationKey, $value);
		}

		return '; ' . implode('; ', $configurationItems);
	}
}
?>