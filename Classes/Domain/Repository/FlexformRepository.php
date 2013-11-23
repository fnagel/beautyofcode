<?php
namespace TYPO3\Beautyofcode\Domain\Repository;

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
 * The repository for the plugin flexform domain model object
 *
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class FlexformRepository {

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
	 */
	public function injectFlexformService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexformService) {
		$this->flexformService = $flexformService;
	}

	/**
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
	 */
	public function injectDataMapper(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper) {
		$this->dataMapper = $dataMapper;
	}

	/**
	 *
	 * @param \TYPO3\CMS\Core\ \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject
	 * @return \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	public function reconstituteByContentObject(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject) {
		$flexformString = $contentObject->data['pi_flexform'];

		$flexformValues = $this->flexformService->convertFlexFormContentToArray($flexformString);

		$flexformValues = $this->getDataMapperToTCACompatiblePropertyArray($flexformValues);
		// adds `identity` to the plugin configuration
		$flexformValues['uid'] = $contentObject->data['uid'];

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
	protected function getDataMapperToTCACompatiblePropertyArray($flexformValueArray) {
		$flexformValues = array();

		foreach ($flexformValueArray as $propertyName => $propertyValue) {
			$propertyValue = str_replace('settings.', '', $propertyValue);
			$propertyNameLowerCaseUnderscored = \TYPO3\CMS\Core\Utility\GeneralUtility::camelCaseToLowerCaseUnderscored($propertyName);

			$flexformValues[$propertyNameLowerCaseUnderscored] = $propertyValue;
		}

		return $flexformValues;
	}
}
?>