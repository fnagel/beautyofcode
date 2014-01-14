<?php
namespace TYPO3\Beautyofcode\Configuration\Brush;

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
 * Loads all prism library brushes
 *
 * @package \TYPO3\Beautyofcode\Configuration\Brush
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PrismBrush {

	protected $brushesPath = 'EXT:beautyofcode/Resources/Public/Javascript/vendor/prism/components/';

	protected $discoveredBrushes = array();

	protected $aliases = array(
		'bash' => 'Bash / Shell',
		'c' => 'Ansi-C',
		'clike' => 'C#-Like',
		'coffeescript' => 'CoffeScript',
		'cpp' => 'C++',
		'csharp' => 'C#',
		'css-extras' => 'CSS Extras',
		'css' => 'CSS',
		'gherkin' => 'Gherkin',
		'go' => 'Go',
		'groovy' => 'Groovy',
		'http' => 'HTTP',
		'Php' => 'PHP',
		'java' => 'Java',
		'javascript' => 'JavaScript',
		'markup' => 'XML / XSLT / XHTML / HTML',
		'php-extras' => 'PHP Extras',
		'php' => 'PHP',
		'python' => 'Python',
		'ruby' => 'Ruby',
		'scss' => 'Sass / SCSS',
		'sql' => 'MySQL / SQL',
		'typoscript' => 'TypoScript',
	);

	public function getBrushes() {
		$this->discoverBrushes();

		$brushes = array();

		foreach ($this->discoveredBrushes as $discoveredBrush) {
			$brushName = str_replace('prism-', '', $discoveredBrush);
			$brushName = str_replace('.js', '', $brushName);

			if (TRUE === array_key_exists($brushName, $this->aliases)) {
				$brushes[$brushName] = $this->aliases[$brushName];
			} else {
				$brushes[$brushName] = $brushName;
			}
		}

		asort($brushes);

		return $brushes;
	}

	protected function discoverBrushes() {
		$path = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->brushesPath);

		$this->discoveredBrushes = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir(
			$path,
			'js',
			FALSE,
			'1',
			'.*(core|extras|\.min)\.js'
		);
	}
}
?>