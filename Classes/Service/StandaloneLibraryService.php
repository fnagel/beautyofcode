<?php
namespace FNagel\Beautyofcode\Service;

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
class StandaloneLibraryService extends \FNagel\Beautyofcode\Service\AbstractLibraryService {

	/**
	 *
	 * @var string
	 */
	protected $templatePathAndFilename = 'typo3conf/ext/beautyofcode/Resources/Private/Templates/Inline/Standalone.js';

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
	 * @see \FNagel\Beautyofcode\Service\AbstractLibraryService::load()
	 */
	public function load() {
		$this->checkAndPossiblyOverrideFilePaths();

		$this->addJavascriptLibaries();

		$this->addStylesheets();

		$this->loadBrushes();

		$this->renderAndAddInlineJavascript();
	}

	/**
	 * Checks if the configured paths are empty, if not override
	 *
	 * @return void
	 */
	protected function checkAndPossiblyOverrideFilePaths() {
		$isBaseUrlSet = strlen(trim($this->configuration['baseUrl'])) > 0;
		$isStylePathSet = strlen(trim($this->configuration['styles'])) > 0;
		$isScriptPathSet = strlen(trim($this->configuration['scripts'])) > 0;

		$overridePaths = $isBaseUrlSet && $isStylePathSet && $isScriptPathSet;

		if ($overridePaths) {
			$this->filePathBase = $this->bocGeneralUtility->makeAbsolutePath(
				$this->configuration['baseUrl']
			);
			$this->filePathScripts = trim($this->configuration['scripts']);
			$this->filePathStyles = trim($this->configuration['styles']);
		}
	}

	/**
	 *
	 * @return void
	 */
	protected function addJavascriptLibaries() {
		$this->pageRenderer->addJsLibrary(
			'beautyofcode_JS_shCoreJS',
			$this->filePathBase . $this->filePathScripts . 'shCore.js'
		);

		$this->pageRenderer->addJsLibrary(
			'beautyofcode_JS_shAutoloader',
			$this->filePathBase . $this->filePathScripts . 'shAutoloader.js'
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
			$this->filePathBase . $this->filePathStyles . 'shCore.css'
		);
		$this->pageRenderer->addCssFile(
			$this->filePathBase . $this->filePathStyles . $cssStyleFile
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
	 *
	 * @return void
	 */
	protected function renderAndAddInlineJavascript() {
		$cacheId = md5(serialize($this->configuration));

		if ($this->cacheManager->getCache('cache_beautyofcode')->has($cacheId)) {
			$resource = $this->cacheManager->getCache('cache_beautyofcode')->get($cacheId);
		} else {
			/* @var $view \TYPO3\CMS\Fluid\View\StandaloneView */
			$view = $this->objectManager->get(
				'TYPO3\\CMS\\Fluid\\View\\StandaloneView',
				$this->configurationManager->getContentObject()
			);

			$view->setFormat('js');
			$view->setTemplatePathAndFilename($this->templatePathAndFilename);

			$view->assignMultiple(array(
				'settings' => $this->configuration,
				'brushes' => $this->brushes,
				'filePathBase' => $this->filePathBase,
				'filePathScripts' => $this->filePathScripts,
			));

			$resource = $view->render();

			$this->cacheManager
				->getCache('cache_beautyofcode')
				->set($cacheId, $resource, array(), 0);
		}

		$this->pageRenderer->addJsInlineCode('beautyofcode_inline', $resource);
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\AbstractLibraryService::getCssConfig()
	 */
	public function getClassAttributeConfiguration($config = array()) {
		$string = '; ';

		foreach ($config as $configKey => $configValue) {
			// skip unavailable SyntaxHighlighter v3 configuration keys
			if (($configValue == '' || $configValue == 'auto') || $configKey == 'toolbar') {
				continue;
			}

			// highlight range
			if ($configKey == 'highlight') {
				$string .= sprintf('highlight: [%s]; ',
					\TYPO3\CMS\Core\Utility\GeneralUtility::expandList($configValue)
				);
			} else {
				$string .= sprintf('%s: %s; ',
					$configKey,
					var_export((boolean) $configValue, TRUE)
				);
			}
		}

		$string = substr($string, 0, -2);

		return $string;
	}
}
?>