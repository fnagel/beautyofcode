<?php

namespace FelixNagel\Beautyofcode\Domain\Repository;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use FelixNagel\Beautyofcode\Domain\Model\Flexform;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * The repository for the plugin flexform domain model object.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class FlexformRepository
{
    /**
     * FlexFormService.
     *
     * @var FlexFormService
     */
    protected $flexformService;

    /**
     * ObjectManager.
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Injects the flexform service and populates flexform values from `pi_flexform`.
     *
     * @param FlexFormService $flexformService FlexFormService
     */
    public function injectFlexformService(FlexFormService $flexformService)
    {
        $this->flexformService = $flexformService;
    }

    /**
     * InjectObjetManager.
     *
     * @param ObjectManagerInterface $objectManager ObjectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }


    /**
     * ReconstituteByContentObject.
     *
     * @param ContentObjectRenderer $contentObject ContentObjectRenderer
     *
     * @return Flexform
     */
    public function reconstituteByContentObject(ContentObjectRenderer $contentObject)
    {
        $flexformString = $contentObject->data['pi_flexform'];

        $flexformValues = $this->flexformService->convertFlexFormContentToArray($flexformString);

        $flexform = $this->objectManager->getEmptyObject(Flexform::class);
        $flexform->initializeObject($flexformValues);

        return $flexform;
    }

    /**
     * Returns a DataMapper-to-TCA compatible property array out of flexform values.
     *
     * Basically, this transforms CamelCased property names into camel_cased ones.
     *
     * @param array $flexformValueArray Flexform value array
     *
     * @return array
     */
    protected function getDataMapperToTCACompatiblePropertyArray($flexformValueArray)
    {
        $flexformValues = [];

        foreach ($flexformValueArray as $propertyName => $propertyValue) {
            $propertyNameLowerCaseUnderscored = GeneralUtility::camelCaseToLowerCaseUnderscored(
                $propertyName
            );

            $flexformValues[$propertyNameLowerCaseUnderscored] = $propertyValue;
        }

        return $flexformValues;
    }
}
