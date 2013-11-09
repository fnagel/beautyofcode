<?php
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
 * Updates tt_content fields
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ext_update {

	/**
	 *
	 * @param string $what
	 * @return boolean
	 */
	public function access($what = 'all') {
		/* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
		$db = $GLOBALS['TYPO3_DB'];
		$count = $db->exec_SELECTcountRows('*', 'tt_content', 'list_type = "beautyofcode_pi1"');

		return $count > 0;
	}

	/**
	 *
	 * @return string
	 */
	public function main() {
		/* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
		$db = $GLOBALS['TYPO3_DB'];

		$count = $db->exec_SELECTcountRows('*', 'tt_content', 'list_type = "beautyofcode_pi1"');

		if (0 < $count) {
			$db->exec_UPDATEquery('tt_content', 'list_type = "beautyofcode_pi1"', array('list_type' => 'beautyofcode_contentrenderer'));

			$output = sprintf('Updated %s tt_content records', $count);
		} else {
			$output = 'Nothing needs to be updated.';
		}

		return $output;
	}
}
?>