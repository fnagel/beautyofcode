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
 * Loads all brushes if the jquery version is used
 *
 * @package \TYPO3\Beautyofcode\Configuration\Brush
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class JqueryBrush {

	protected $brushesPath = 'EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v2/scripts/';

	protected $discoveredBrushes = array();

	protected $aliases = array(
		'AS3' => 'Actionscript 3',
		'Bash' => 'Bash / Shell',
		'ColdFusion' => 'ColdFusion',
		'Cpp' => 'C / C++',
		'CSharp' => 'C#',
		'Css' => 'CSS',
		'Delphi' => 'Delphi / Pas / Pascal',
		'Diff' => 'Diff / Patch',
		'Erlang' => 'Erlang',
		'Groovy' => 'Groovy',
		'Java' => 'Java',
		'JavaFX' => 'Java FX',
		'JScript' => 'Javascript',
		'Perl' => 'Perl',
		'Php' => 'PHP',
		'PowerShell' => 'Power-Shell',
		'Python' => 'Python',
		'Ruby' => 'Ruby on Rails',
		'Scala' => 'Scala',
		'Sql' => 'SQL / MySQL',
		'Typoscript' => 'TypoScript',
		'Vb' => 'Visual Basic / .Net',
		'Xml' => 'XML / XSLT / XHTML / HTML',
	);

	public function getBrushes() {
		$this->discoverBrushes();

		$brushes = array();

		foreach ($this->discoveredBrushes as $discoveredBrush) {
			$brushName = str_replace('shBrush', '', $discoveredBrush);
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
			'sh(BrushPlain|Core|Legacy)\.js'
		);
	}
}
?>