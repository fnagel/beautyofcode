<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Updates tt_content records
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ext_update {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	/**
	 *
	 * @var integer
	 */
	protected $countOldPlugins;

	/**
	 *
	 * @var integer
	 */
	protected $countOldFlexformConfigurations;


	/**
	 * Stack of old flexform configuration key signatures
	 *
	 * These signatures get wrapped by '%<|>%' in the where clause of the old
	 * flexform configuration detection/updating.
	 *
	 * @var array
	 */
	protected $oldFlexformConfigurationKeySignatures = array(
		'cLabel',
		'cLang',
		'cCode',
		'cHighlight',
		'cCollapse',
		'cGutter',
		'cToolbar',
	);

	/**
	 * initializes the updater
	 *
	 * @return void
	 */
	protected function initialize() {
		if (FALSE === isset($this->db)) {
			$this->db = $GLOBALS['TYPO3_DB'];
		}
	}

	/**
	 * Checks if the update script must be run
	 *
	 * @return boolean
	 */
	public function access() {
		$this->initialize();

		$hasOldPlugins = $this->hasInstanceOldPlugins();
		$hasOldFlexformConfigurations = $this->hasInstanceOldFlexformConfigurations();

		return $hasOldPlugins || $hasOldFlexformConfigurations;
	}

	/**
	 * Counts the amount of old plugin instances within tt_content records
	 *
	 * @return boolean
	 */
	protected function hasInstanceOldPlugins() {
		$this->countOldPlugins = $this->db->exec_SELECTcountRows('*', 'tt_content', 'list_type = "beautyofcode_pi1"');

		return 0 < $this->countOldPlugins;
	}

	/**
	 * Counts the amount of old flexform configuration string within tt_content records
	 *
	 * @return boolean
	 */
	protected function hasInstanceOldFlexformConfigurations() {
		$oldConfigurationWhereClause = $this->getOldFlexformConfigurationsWhereClause();

		$this->countOldFlexformConfigurations = $this->db->exec_SELECTcountRows('*', 'tt_content', $oldConfigurationWhereClause);

		return 0 < $this->countOldFlexformConfigurations;
	}

	/**
	 * Builds a where clause to query old flexform configuration strings
	 *
	 * @return string
	 */
	protected function getOldFlexformConfigurationsWhereClause() {
		$oldConfigurationKeyClauseParts = array();
		foreach ($this->oldFlexformConfigurationKeySignatures as $keySignature) {
			$oldConfigurationKeyClauseParts[] = 'pi_flexform LIKE "%<' . $keySignature . '>%';
		}

		return sprintf('%s AND (%s)',
			'list_type = "beautyofcode_pi1" OR list_type = "beautyofcode_contentrenderer"',
			// search by ANDing: all keys must be found in the flexform string...
			implode(' AND ', $oldConfigurationKeyClauseParts)
		);
	}

	/**
	 * Executes the update script
	 *
	 * @return string
	 */
	public function main() {
		$this->initialize();

		$output = '';

		if ($this->hasInstanceOldPlugins()) {
			$output .= $this->updateOldPlugins();
		}

		if ($this->hasInstanceOldFlexformConfigurations()) {
			$output .= $this->updateOldFlexformConfigurations();
		}

		if ($output === '') {
			$output = 'Nothing needs to be updated.';
		}

		return $output;
	}

	/**
	 * Updates tt_content records by setting `list_type` to new plugin signature
	 *
	 * @return string
	 */
	protected function updateOldPlugins() {
		$this->db->exec_UPDATEquery('tt_content', 'list_type = "beautyofcode_pi1"', array('list_type' => 'beautyofcode_contentrenderer'));

		return sprintf('<p>Updated plugin signature of %s tt_content records.</p>', $this->countOldPlugins);
	}

	/**
	 * Updates tt_content records by replacing old configuration keys within the flexform field
	 *
	 * @return string
	 */
	protected function updateOldFlexformConfigurations() {
		$whereClause = $this->getOldFlexformConfigurationsWhereClause();

		$rows = $this->db->exec_SELECTgetRows('uid, pi_flexform', 'tt_content', $whereClause);

		if (TRUE === is_null($rows)) {
			return '';
		}

		$countSuccessfulUpdates = 0;

		foreach ($rows as $row) {
			$updatedFlexformString = $this->replaceOldFlexformConfigurationKeySignatures($row['pi_flexform']);
			$updateResult = $this->db->exec_UPDATEquery('tt_content', 'uid = ' . $row['uid'], array('pi_flexform' => $updatedFlexformString));

			if (TRUE === $updateResult) {
				$countSuccessfulUpdates++;
			}
		}

		return sprintf('<p>Found %s old flexform configurations. Updated %s of them.</p>',
			$this->countOldFlexformConfigurations,
			$countSuccessfulUpdates
		);
	}

	/**
	 * Updates the old flexform configuration key signatures in the incoming flexform configuration string
	 *
	 * @param string $inputFlexform
	 * @return string
	 */
	protected function replaceOldFlexformConfigurationKeySignatures($inputFlexform) {
		$outputFlexform = $inputFlexform;

		foreach ($this->oldFlexformConfigurationKeySignatures as $keySignature) {
			$search = array('<' . $keySignature . '>', '</' . $keySignature . '>');
			$replace = array('<settings.' . $keySignature . '>', '</settings.' . $keySignature . '>');

			$outputFlexform = str_replace($search, $replace, $outputFlexform);
		}

		return $outputFlexform;
	}
}
?>