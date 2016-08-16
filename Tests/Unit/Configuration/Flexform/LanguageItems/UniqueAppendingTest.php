<?php

namespace TYPO3\Beautyofcode\Configuration\Flexform\LanguageItems;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
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

use TYPO3\Beautyofcode\Service\SettingsService;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Tests the unique addition of brushes to the flexform items array.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class UniqueAppendingTest extends UnitTestCase
{
    /**
     * @test
     */
    public function configuredBrushesAreUniquelyAddedToTheReturnValue()
    {
        /* @var $settingsServiceMock SettingsService|\PHPUnit_Framework_MockObject_MockObject */
        $settingsServiceMock = $this->createMock(SettingsService::class);

        /* @var $objectManagerMock \TYPO3\CMS\Extbase\Object\ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
        $objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $objectManagerMock
                ->expects($this->once())->method('get')
                ->with(
                    $this->equalTo('TYPO3\\Beautyofcode\\Service\\SettingsService'),
                    $this->equalTo(1)
                )
                ->will($this->returnValue($settingsServiceMock));

        $cacheBackendMock = new \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend('Testing');
        $cacheFrontendMock = new \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend('cache_beautyofcode', $cacheBackendMock);
        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock
            ->expects($this->any())
            ->method('getCache')
            ->with($this->equalTo('cache_beautyofcode'))
            ->willReturn($cacheFrontendMock);

        /* @var $highlighterConfigurationMock \TYPO3\Beautyofcode\Highlighter\Configuration\SyntaxHighlighter|\PHPUnit_Framework_MockObject_MockObject */
        $highlighterConfigurationMock = $this
            ->getMockBuilder('TYPO3\\Beautyofcode\\Highlighter\\Configuration\\SyntaxHighlighter')
            ->disableOriginalConstructor()
            ->getMock();
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('hasBrushIdentifier')
            ->will($this->returnValue(true));
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('getBrushIdentifierAliasAndLabel')
            ->will($this->returnValue(array('SQL / MySQL' => 'sql')));

        $sut = new \TYPO3\Beautyofcode\Configuration\Flexform\LanguageItems();
        $sut->injectObjectManager($objectManagerMock);
        $sut->injectCacheManager($cacheManagerMock);
        $sut->injectHighlighterConfiguration($highlighterConfigurationMock);

        $configFromFlexform = array(
            'row' => array(
                'uid' => 1,
                'pid' => 1,
            ),
            'items' => array(
                array(
                    'Plain', // TCEforms: label
                    'plain', // TCEforms: key
                ),
            ),
        );

        $newConfig = $sut->getConfiguredLanguages($configFromFlexform);

        $this->assertEquals('plain', $newConfig['items'][0][1]);
        $this->assertEquals(1, count($newConfig['items']));
    }
}
