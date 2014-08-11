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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;

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
	 * @var ConfigurationInterface
	 */
	protected $highlighterConfiguration;

	/**
	 *
	 * @var array
	 */
	protected $discoveryConfiguration = array();

	/**
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
	 * injectHighlighterConfiguration
	 *
	 * @param ConfigurationInterface $highlighterConfiguration
	 * @return void
	 */
	public function injectHighlighterConfiguration(ConfigurationInterface $highlighterConfiguration) {
		$this->highlighterConfiguration = $highlighterConfiguration;
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
	}

	/**
	 * Discovers brushes and returns them
	 *
	 * @return array Multidimensional array with library as keys, brushes stack
	 *               as value where key is the name, value is an LLL alias
	 */
	public function discoverBrushes() {
		if (!empty($this->brushStack)) {
			return $this->brushStack;
		}

		foreach ($this->discoveryConfiguration as $library => $libraryConfiguration) {
			$brushes = $this->findBrushes($libraryConfiguration);

			$this->brushStack[$library] = $this->filterAndSortBrushes(
				$brushes,
				$libraryConfiguration
			);
		}

		return $this->brushStack;
	}

	/**
	 * Finds brushes in the file system
	 *
	 * @param array $configuration Library brush discovery configuration array
	 * @return array An array of brush file names
	 */
	protected function findBrushes($configuration) {
		$absoluteBrushesPath = GeneralUtility::getFileAbsFileName(
			$configuration['path']
		);

		return GeneralUtility::getFilesInDir(
			$absoluteBrushesPath,
			'js',
			FALSE,
			'1',
			$configuration['excludePattern']
		);
	}

	/**
	 * Filters and sorts the brushes
	 *
	 * Filtering is done by stripping prefix & suffix according to discovery
	 * configuration array. The TYPO3.CMS language service is used to fetch a
	 * brush alias. After that, the (translated) brushes are sorted alphabetically.
	 *
	 * @param array $brushes
	 * @param array $configuration The library brush discovery configuration array
	 * @return array
	 */
	protected function filterAndSortBrushes($brushes, $configuration) {
		$filteredAndSortedBrushes = array();

		foreach ($brushes as $brush) {
			$brushIdentifier = str_replace($configuration['prefix'], '', $brush);
			$brushIdentifier = str_replace($configuration['suffix'], '', $brushIdentifier);

			/* @var $languageService \TYPO3\CMS\Lang\LanguageService */
			$languageService = $GLOBALS['LANG'];

			$brushLabel = $languageService->sL(
				self::BRUSH_LABELS_CATALOGUE . ':' . $brushIdentifier
			);

			if ('' === $brushLabel) {
				$brushLabel = $brushIdentifier;
			}

			$brushAlias = $this->highlighterConfiguration->getBrushAliasByIdentifier($brushIdentifier);
			$filteredAndSortedBrushes[$brushAlias] = $brushLabel;
		}

		asort($filteredAndSortedBrushes);

		return $filteredAndSortedBrushes;
	}

	/**
	 * discoverDependencies
	 *
	 * @return array
	 */
	public function discoverDependencies() {
		if (!empty($this->dependencies)) {
			return $this->dependencies;
		}

		foreach ($this->discoveryConfiguration as $library => $libraryConfiguration) {
			$this->dependencies[$library] = $libraryConfiguration['dependencies'];
		}

		return $this->dependencies;
	}
}