<?php
namespace TYPO3\Beautyofcode\Highlighter\Configuration;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Prism
 *
 * @package \TYPO3\Beautyofcode\Highlighter\Configuration
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class Prism extends AbstractConfiguration {

	/**
	 * getClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform) {
		$configurationItems = array();
		$classAttributeConfigurationStack = array(
			'data-line' => GeneralUtility::expandList($flexform->getCHighlight()),
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			$configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
		}

		return ' ' . implode(' ', $configurationItems);
	}

	/**
	 * getPlainBrushIdentifier
	 *
	 * @return string
	 */
	protected function getPlainBrushIdentifier() {
		return 'plain';
	}
}