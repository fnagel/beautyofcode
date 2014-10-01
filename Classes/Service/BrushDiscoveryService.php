<?php
namespace TYPO3\Beautyofcode\Service;

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

use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * The brush discovery service
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class BrushDiscoveryService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 *
	 * @var string
	 */
	const BRUSH_LABELS_CATALOGUE = 'LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf';

	/**
	 *
	 * @var \TYPO3\CMS\Lang\LanguageService
	 */
	protected $languageService;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Utility\FileFinderUtility
	 */
	protected $fileFinderUtility;

	/**
	 *
	 * @var array
	 */
	protected $discoveryConfiguration = array();

	/**
	 * Multidimensional array with library as keys, brushes stack
	 * as value where key is the name, value is an LLL alias
	 *
	 * @var array
	 */
	protected $brushStack = array();

	/**
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * __construct
	 *
	 * @param \TYPO3\CMS\Lang\LanguageService $languageService
	 * @return \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	public function __construct(\TYPO3\CMS\Lang\LanguageService $languageService = NULL) {
		if (is_null($languageService)) {
			$languageService = $GLOBALS['LANG'];
		}

		$this->languageService = $languageService;
	}

	/**
	 * injectFileFinderUtility
	 *
	 * @param \TYPO3\Beautyofcode\Utility\FileFinderUtility $fileFinderUtility
	 * @return void
	 */
	public function injectFileFinderUtility(\TYPO3\Beautyofcode\Utility\FileFinderUtility $fileFinderUtility) {
		$this->fileFinderUtility = $fileFinderUtility;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		try {
			$this->discoveryConfiguration = ArrayUtility::getValueByPath(
				$GLOBALS,
				'TYPO3_CONF_VARS/EXTCONF/beautyofcode/BrushDiscovery'
			);
		} catch (\RuntimeException $e) {
			$this->discoveryConfiguration = array();
		}

		$this->discoverBrushes();
		$this->discoverDependencies();
	}

	/**
	 * Discovers brushes and returns them
	 *
	 * @return void
	 */
	protected function discoverBrushes() {
		foreach ($this->discoveryConfiguration as $library => $libraryConfiguration) {
			$brushes = $this->fileFinderUtility
				->in($libraryConfiguration['path'])
				->absolutize()
				->exclude($libraryConfiguration['excludePattern'])
				->find('js');

			array_walk($brushes, array($this, 'removeFromFilename'), $libraryConfiguration['prefix']);
			array_walk($brushes, array($this, 'removeFromFilename'), $libraryConfiguration['suffix']);

			$brushes = $this->getSortedIdentifiersByTranslation($brushes);

			$this->brushStack[$library] = $brushes;
		}
	}

	/**
	 * Removes the given $search value from the given $fileName
	 *
	 * @param string &$fileName
	 * @param mixed $key
	 * @param string $search
	 * @return void
	 */
	protected function removeFromFilename(&$fileName, $key, $search) {
		$fileName = str_replace($search, '', $fileName);
	}

	/**
	 * getSortedIdentifiersByTranslation
	 *
	 * @param array $brushIdentifiers
	 * @return array
	 */
	public function getSortedIdentifiersByTranslation(array $brushIdentifiers) {
		$brushIdentifiers = array_values($brushIdentifiers);
		$brushLabels = $this->getBrushLabelsForIdentifiers($brushIdentifiers);

		$brushes = array_combine($brushIdentifiers, $brushLabels);
		asort($brushes);

		return $brushes;
	}

	/**
	 * getBrushLabelsForIdentifiers
	 *
	 * @param array $brushIdentifiers
	 * @return array
	 */
	protected function getBrushLabelsForIdentifiers(array $brushIdentifiers) {
		array_walk($brushIdentifiers, array($this, 'translateBrushIdentifier'));
		return array_values($brushIdentifiers);
	}

	/**
	 * translateBrushIdentifier
	 *
	 * @param string &$brushIdentifier
	 * @return void
	 */
	protected function translateBrushIdentifier(&$brushIdentifier) {
		$brushLabel = $this->languageService->sL(
			self::BRUSH_LABELS_CATALOGUE . ':' . $brushIdentifier
		);

		if ('' === $brushLabel) {
			$brushLabel = $brushIdentifier;
		}

		$brushIdentifier = $brushLabel;
	}

	/**
	 * discoverDependencies
	 *
	 * @return void
	 */
	protected function discoverDependencies() {
		foreach ($this->discoveryConfiguration as $library => $libraryConfiguration) {
			$this->dependencies[$library] = $libraryConfiguration['dependencies'];
		}
	}

	/**
	 * getBrushes
	 *
	 * @return array
	 */
	public function getBrushes() {
		return $this->brushStack;
	}

	/**
	 * getDependencies
	 *
	 * @return array
	 */
	public function getDependencies() {
		return $this->dependencies;
	}
}