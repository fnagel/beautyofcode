<?php
namespace TYPO3\Beautyofcode\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * Provides a service to map the beautyofcode flexform string onto a proper DO
 *
 * @category Category
 * @package \TYPO3\Beautyofcode\Service
 * @subpackage Subpackage
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FlexformDataMapperService {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 */
	protected $flexformService;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
	 */
	protected $dataMapper;

	/**
	 * Injects the flexform service and populates flexform values from `pi_flexform`
	 *
	 * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexformService
	 * @return void
	 */
	public function injectFlexformService(
		\TYPO3\CMS\Extbase\Service\FlexFormService $flexformService
	) {
		$this->flexformService = $flexformService;
	}

	/**
	 * injectDataMapper
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
	 * @return void
	 */
	public function injectDataMapper(
		\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
	) {
		$this->dataMapper = $dataMapper;
	}

	/**
	 * map
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\ContentElement $contentElement
	 * @return \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	public function map(
		\TYPO3\Beautyofcode\Domain\Model\ContentElement $contentElement
	) {
		$flexformString = $contentElement->getFlexform();

		$flexformValues = $this->flexformService->convertFlexFormContentToArray(
			$flexformString
		);

		$flexformValues = $this->getDataMapperToTCACompatiblePropertyArray(
			$flexformValues
		);
		// adds `identity` to the plugin configuration
		$flexformValues['uid'] = $contentElement->getUid();

		$flexform = $this
			->dataMapper
			->map(
				'TYPO3\\Beautyofcode\\Domain\\Model\\Flexform',
				array($flexformValues) // nested array as ::map() expects multiple rows
			);

		return $flexform[0];
	}

	/**
	 * Returns a DataMapper-to-TCA compatible property array out of flexform values
	 *
	 * Basically, this transforms CamelCased property names into camel_cased ones.
	 *
	 * @param array $flexformValueArray
	 * @return array
	 */
	protected function getDataMapperToTCACompatiblePropertyArray(
		$flexformValueArray
	) {
		$flexformValues = array();

		foreach ($flexformValueArray as $propertyName => $propertyValue) {
			$propertyNameLowerCaseUnderscored = GeneralUtility::camelCaseToLowerCaseUnderscored(
				$propertyName
			);

			$flexformValues[$propertyNameLowerCaseUnderscored] = $propertyValue;
		}

		return $flexformValues;
	}
}
?>