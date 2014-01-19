<?php
namespace TYPO3\Beautyofcode\Update;

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
 * The language setting update class
 *
 * @package \TYPO3\Beautyofcode\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LanguageSetting extends \TYPO3\Beautyofcode\Update\AbstractUpdate {

	protected $plugins = array();

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::initializeObject()
	 */
	public function initializeObject() {
		$this->plugins = $this->db->exec_SELECTquery(
			'uid, header, pi_flexform',
			'tt_content',
			sprintf(
				'list_type IN (%s)',
				'\'' . implode('\',\'', array('beautyofcode_pi1', 'beautyofcode_contentrenderer')) . '\''
			)
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::getInformation()
	 */
	public function getInformation() {
		$output = '';

		$output .= '<h4>Update language/syntax associations.</h4>';

		$output .= '<label><input type="checkbox" name="update[language]" value="1"> Update plugin language settings according to the following settings:</label><br />';

		$tableBody = '';
		while ($plugin = $this->db->sql_fetch_assoc($this->plugins)) {
			$tableBody .= $this->getInformationTableRow($plugin);
		}

		$output .= $this->wrapTableBody($tableBody);

		return $output;
	}

	/**
	 * Returns a table row for each discovered plugin from tt_content
	 *
	 * @param array $plugin Row of tt_content table
	 * @return string
	 */
	protected function getInformationTableRow($plugin) {
		$flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($plugin['pi_flexform']);
		$flexformSettings = $flexformData['data']['sDEF']['lDEF'];

		if ('' !== $flexformSettings['cLabel']['vDEF']) {
			$title = $flexformSettings['cLabel']['vDEF'];
		} else if ('' !== $plugin['header']) {
			$title = $plugin['header'];
		} else {
			$title = '(Untitled)';
		}

		return '<tr>
			<td><input type="hidden" name="languages[' . $plugin['uid'] . ']" value="1" /></td>
			<td>' . $title . '</td>
			<td>' . $flexformSettings['cLang']['vDEF'] . '</td>
			<td>' . $this->getAvailableBrushes($flexformSettings['cLang']['vDEF']) . '</td>
			<td>' . substr($flexformSettings['cCode']['vDEF'], 0, 32) . '...</td>
		</tr>';
	}

	/**
	 * Returns a select list with all available brushes
	 *
	 * @param string $currentBrush
	 * @return string
	 */
	protected function getAvailableBrushes($currentBrush) {
		$output = '';

		$options = '';

		$selected = '';

		/* @var $brushDiscoveryService \TYPO3\Beautyofcode\Service\BrushDiscoveryService */
		$brushDiscoveryService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService');
		$libraries = $brushDiscoveryService->discoverBrushes();

		foreach ($libraries as $library => $brushes) {
			$options .= '<optgroup label="' . $library . '">';

			foreach ($brushes as $brushName => $brushAlias) {
				if ('' === $selected && strtolower($brushName) === $currentBrush) {
					$selected = ' selected="selected"';
				}

				$options .= sprintf('<option value="%s"%s>%s</option>',
					$brushName,
					$selected,
					$brushAlias
				);
			}

			$options .= '</optgroup>';
		}

		$output = '<select name="languages[]" size="1">' . $options . '</select>';

		return $output;
	}

	/**
	 * Wraps the given $tableBody in the language matrix table markup
	 *
	 * @param string $tableBody
	 * @return string
	 */
	protected function wrapTableBody($tableBody) {
		return '<table class="typo3-dblist" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr class="t3-row-header">
					<th>&nbsp;</th>
					<th>Title</th>
					<th>Current language</th>
					<th>New language</th>
					<th>Code excerpt</th>
				</tr>
			</thead>
			<tbody>
				' . $tableBody . '
			</tbody>
		</table>';
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::execute()
	 */
	public function execute() {
		return '';
	}
}
?>