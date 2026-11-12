<?php

namespace FelixNagel\Beautyofcode\Domain\Repository;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Domain\Model\Flexform;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * The repository for the plugin flexform domain model object.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class FlexformRepository
{
    protected FlexFormTools $flexformService;

    public function injectFlexformService(FlexFormTools $flexformService)
    {
        $this->flexformService = $flexformService;
    }

    public function reconstituteByContentObject(ContentObjectRenderer $contentObject): Flexform
    {
        // @extensionScannerIgnoreLine
        $flexformString = $contentObject->data['pi_flexform'];

        $flexformValues = $this->flexformService->convertFlexFormContentToArray($flexformString);

        /* @var $flexform Flexform */
        $flexform = GeneralUtility::makeInstance(Flexform::class);
        $flexform->initializeObject($flexformValues);

        return $flexform;
    }

    /**
     * Returns a DataMapper-to-TCA compatible property array out of flexform values.
     *
     * Basically, this transforms CamelCased property names into camel_cased ones.
     */
    protected function getDataMapperToTCACompatiblePropertyArray(array $flexformValueArray): array
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
