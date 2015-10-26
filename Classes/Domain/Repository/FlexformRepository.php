<?php
namespace TYPO3\Beautyofcode\Domain\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * The repository for the plugin flexform domain model object
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Domain\Repository
 */
class FlexformRepository {

	/**
	 * FlexFormService
	 *
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 */
	protected $flexformService;

	/**
	 * DataMapper
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
	 */
	protected $dataMapper;

	/**
	 * Injects the flexform service and populates flexform values from `pi_flexform`
	 *
	 * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexformService FlexFormService
	 *
	 * @return void
	 */
	public function injectFlexformService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexformService) {
		$this->flexformService = $flexformService;
	}

	/**
	 * InjectDataMapper
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper DataMapper
	 *
	 * @return void
	 */
	public function injectDataMapper(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper) {
		$this->dataMapper = $dataMapper;
	}

	/**
	 * ReconstituteByContentObject
	 *
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject ContentObjectRenderer
	 *
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
	 * @param array $flexformValueArray Flexform value array
	 *
	 * @return array
	 */
	protected function getDataMapperToTCACompatiblePropertyArray($flexformValueArray) {
		$flexformValues = array();

		foreach ($flexformValueArray as $propertyName => $propertyValue) {
			$propertyNameLowerCaseUnderscored = \TYPO3\CMS\Core\Utility\GeneralUtility::camelCaseToLowerCaseUnderscored($propertyName);

			$flexformValues[$propertyNameLowerCaseUnderscored] = $propertyValue;
		}

		return $flexformValues;
	}
}
