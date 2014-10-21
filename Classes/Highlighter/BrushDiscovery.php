<?php
namespace TYPO3\Beautyofcode\Highlighter;

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

use TYPO3\CMS\Lang\LanguageService;
use TYPO3\Beautyofcode\Utility\BrushFileFinderUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Discovers brushes and their dependencies for *all* known libraries.
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class BrushDiscovery {

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
	 * @var BrushFileFinderUtility
	 */
	protected $fileFinder;

	/**
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * Multidimensional array with library as keys, brushes stack
	 * as value where key is the name, value is an LLL alias
	 *
	 * @var array
	 */
	protected $brushes = array();

	/**
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * __construct
	 *
	 * @param LanguageService $languageService
	 * @return BrushDiscovery
	 */
	public function __construct(LanguageService $languageService = NULL) {
		if (is_null($languageService)) {
			$languageService = $GLOBALS['LANG'];
		}

		$this->languageService = $languageService;
	}

	/**
	 * injectFileFinderUtility
	 *
	 * @param BrushFileFinderUtility $fileFinderUtility
	 * @return void
	 */
	public function injectFileFinderUtility(BrushFileFinderUtility $fileFinderUtility) {
		$this->fileFinder = $fileFinderUtility;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		try {
			$this->configuration = ArrayUtility::getValueByPath(
				$GLOBALS,
				'TYPO3_CONF_VARS/EXTCONF/beautyofcode/BrushDiscovery'
			);

			$this->discover();
		} catch (\RuntimeException $e) {
			$this->configuration = array();
		}
	}

	/**
	 * Discovers brushes and their dependencies.
	 *
	 * @return void
	 */
	protected function discover() {
		foreach ($this->configuration as $library => $configuration) {
			$fileNames = $this->fileFinder
				->in($configuration['path'])
				->absolutize()
				->exclude($configuration['excludePattern'])
				->stripFromFilename($configuration['prefix'])
				->stripFromFilename($configuration['suffix'])
				->find('js');

			$identifiers = array_values($fileNames);

			$this->brushes[$library] = $this->getBrushesSortedByIdentifiersLabels($identifiers);
			$this->dependencies[$library] = $configuration['dependencies'];
		}
	}

	/**
	 * getBrushesSortedByIdentifiersLabels
	 *
	 * @param array $identifiers
	 * @return array
	 */
	public function getBrushesSortedByIdentifiersLabels(array $identifiers) {
		$labels = $this->getLabelsForIdentifiers($identifiers);

		$brushes = array_combine($identifiers, $labels);
		asort($brushes);

		return $brushes;
	}

	/**
	 * getLabelsForIdentifiers
	 *
	 * @param array $identifiers
	 * @return array
	 */
	protected function getLabelsForIdentifiers(array $identifiers) {
		array_walk($identifiers, array($this, 'getLabelForIdentifier'));
		return array_values($identifiers);
	}

	/**
	 * getLabelForIdentifier
	 *
	 * @param string &$identifier
	 * @return void
	 */
	protected function getLabelForIdentifier(&$identifier) {
		$label = $this->languageService->sL(
			self::BRUSH_LABELS_CATALOGUE . ':' . $identifier
		);

		if ('' !== $label) {
			$identifier = $label;
		}

	}

	/**
	 * getBrushes
	 *
	 * @param string $library
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getBrushes($library) {
		if (!isset($this->brushes[$library])) {
			throw new \InvalidArgumentException(
				sprintf('No brushes found for the given library %s!', $library),
				1413915158
			);
		}

		return $this->brushes[$library];
	}

	/**
	 * getDependencies
	 *
	 * @param string $library
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getDependencies($library) {
		if (!isset($this->dependencies[$library])) {
			throw new \InvalidArgumentException(
				sprintf('No dependencies found for the given library %s!', $library),
				1413915227
			);
		}

		return $this->dependencies[$library];
	}
}