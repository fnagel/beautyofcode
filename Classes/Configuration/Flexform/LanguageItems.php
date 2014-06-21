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

/**
 * Function to add select options dynamically (loaded in flexform)
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Configuration\Flexform
 */
class LanguageItems {

	/**
	 * A CSS class/label map for the select box
	 *
	 * Key is the brush string from TS Setup; Value is an array with the CSS
	 * class in key 0 and the label for the select box in key 1
	 *
	 * @var array
	 */
	protected $cssClassLabelMap = array(
		'AS3' => array('actionscript3', 'Actionscript 3'),
		'Bash' => array('bash', 'Bash / Shell'),
		'ColdFusion' => array('coldfusion', 'ColdFusion'),
		'Cpp' => array('cpp', 'C / C++'),
		'CSharp' => array('csharp', 'C#'),
		'Css' => array('css', 'CSS'),
		'Delphi' => array('delphi', 'Delphi / Pas / Pascal'),
		'Diff' => array('diff', 'Diff / Patch'),
		'Erlang' => array('erlang', 'Erlang'),
		'Groovy' => array('groovy', 'Groovy'),
		'Java' => array('java', 'Java'),
		'JavaFX' => array('javafx', 'Java FX'),
		'JScript' => array('javascript', 'Java-Script'),
		'Perl' => array('perl', 'Perl'),
		'Php' => array('php', 'PHP'),
		'PowerShell' => array('powershell', 'Power-Shell'),
		'Python' => array('python', 'Python'),
		'Ruby' => array('ruby', 'Ruby on Rails'),
		'Scala' => array('scala', 'Scala'),
		'Sql' => array('sql', 'SQL / MySQL'),
		'Typoscript' => array('typoscript', 'Typoscript'),
		'Vb' => array('vbnet', 'Virtual Basic / .Net'),
		'Xml' => array('xml', 'XML / XSLT / XHTML / HTML'),
	);

	/**
	 *
	 * @var integer
	 */
	protected $contentElementPid;

	/**
	 *
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository
	 */
	protected $pageRepository;

	/**
	 *
	 * @var \TYPO3\CMS\Core\TypoScript\TemplateService
	 */
	protected $templateService;

	/**
	 * Injects the page repository
	 *
	 * @param \TYPO3\CMS\Frontend\Page\PageRepository $pageRepository
	 * @return void
	 */
	public function injectPageRepository(
		\TYPO3\CMS\Frontend\Page\PageRepository $pageRepository = NULL
	) {
		if (is_null($pageRepository)) {
			$pageRepository = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Frontend\\Page\\PageRepository'
			);
		}

		$this->pageRepository = $pageRepository;
	}

	/**
	 * Injects the template service
	 *
	 * @param \TYPO3\CMS\Core\TypoScript\TemplateService $templateService
	 * @return void
	 */
	public function injectTemplateService(
		\TYPO3\CMS\Core\TypoScript\TemplateService $templateService = NULL
	) {
		if (is_null($templateService)) {
			$templateService = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Core\\TypoScript\\TemplateService'
			);
		}

		$this->templateService = $templateService;
	}

	/**
	 * This function is called from the flexform and
	 * adds avaiable programming languages to the select options
	 *
	 * @param array flexform data
	 * @param \TYPO3\CMS\Backend\Form\FormEngine $formEngine
	 * @return array
	 */
	public function getConfiguredLanguages(
		$config,
		\TYPO3\CMS\Backend\Form\FormEngine $formEngine
	) {
		static $cachedFields = 0;

		if ($cachedFields != 0) {
			$config['items'] = $cachedFields;
		} else {

			// make brushes list to flexform selectbox item array
			$optionList = array();

			$recordPid = $config['row']['pid'];
			if ($recordPid < 0) {
				/* @var $editDocumentController \TYPO3\CMS\Backend\Controller\EditDocumentController */
				$editDocumentController = $GLOBALS['SOBE'];
				$recordPid = $editDocumentController->viewId;
			}

			$this->contentElementPid = $recordPid;

			$brushesArray = $this->getUniqueAndSortedBrushes();

			foreach ($brushesArray as $i => $brush) {
				if ($brush === 'Plain') {
					continue;
				}
				// skip unknown brushes
				if (FALSE === isset($this->cssClassLabelMap[$brush])) {
					continue;
				}

				$optionList[$i] = array_reverse($this->cssClassLabelMap[$brush]);
			}

			$config['items'] = array_merge($config['items'], $optionList);
		}
		$cachedFields = $config['items'];

		return $config;
	}

	/**
	 * Returns unique and sorted brushes
	 *
	 * @return array
	 */
	protected function getUniqueAndSortedBrushes() {
		$configArray = $this->getConfig();

		$brushesArray = GeneralUtility::trimExplode(
			',',
			$configArray['brushes'],
			TRUE
		);

		// make unique
		foreach ($brushesArray as &$value) {
			$value = serialize($value);
		}

		$brushesArray = array_unique($brushesArray);

		foreach ($brushesArray as &$value) {
			$value = unserialize($value);
		}

		// sort a-z
		sort($brushesArray);

		return $brushesArray;
	}

	/**
	 * Generates TS Config of the plugin
	 *
	 * @return array
	 */
	protected function getConfig() {
		// Initialize the page selector
		$this->injectPageRepository($this->pageRepository);

		// Initialize the TS template
		$this->injectTemplateService($this->templateService);

		// create dummy TSFE for TemplateService
		$GLOBALS['TSFE'] = new \stdClass();

		$this->pageRepository->init(TRUE);

		$this->templateService->init();

		// Avoid an error
		$this->templateService->tt_track = 0;

		// Get rootline for current PID
		$rootline = $this
			->pageRepository
			->getRootLine(
				$this->contentElementPid
			);

		// Start TS template
		$this->templateService->start($rootline);

		// Generate TS config
		$this->templateService->generateConfig();

		return $this
			->templateService
			->setup['plugin.']['tx_beautyofcode.']['settings.'];
	}
}
?>