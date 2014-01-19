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

/**
 * The brush discovery service
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BrushDiscoveryService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 *
	 * @var string
	 */
	const BRUSH_LABELS_CATALOGUE = 'LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf';

	protected $libraries = array();

	protected $brushStack = array();

	/**
	 * Discovers brushes and returns them
	 *
	 * @return array Multidimensional array with library as keys, brushes stack as value where key is the name, value is an LLL alias
	 */
	public function discoverBrushes() {
		foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] as $library => $libraryConfiguration) {
			$this->libraries[] = $library;

			$brushes = $this->findBrushes($libraryConfiguration);

			$this->brushStack[$library] = $this->filterAndSortBrushes($brushes, $libraryConfiguration);
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
		$absoluteBrushesPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($configuration['path']);

		return \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir(
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
		$_brushes = array();

		foreach ($brushes as $brush) {
			$brushName = str_replace($configuration['prefix'], '', $brush);
			$brushName = str_replace($configuration['suffix'], '', $brushName);

			/* @var $languageService \TYPO3\CMS\Lang\LanguageService */
			$languageService = $GLOBALS['LANG'];

			$brushAlias = $languageService->sL(self::BRUSH_LABELS_CATALOGUE . ':' . $brushName);

			if ('' === $brushAlias) {
				$brushAlias = $brushName;
			}

			$_brushes[$brushName] = $brushAlias;
		}

		asort($_brushes);

		return $_brushes;
	}
}
?>