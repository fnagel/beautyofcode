<?php

namespace FelixNagel\Beautyofcode\Tests\Unit\Configuration\Flexform\LanguageItems;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Highlighter\Configuration\SyntaxHighlighter;
use FelixNagel\Beautyofcode\Service\SettingsService;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use FelixNagel\Beautyofcode\Configuration\Flexform\LanguageItems;

/**
 * Tests the sorted appending of configured brushes to the list of flexform items.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ConfiguredLanguagesTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;

    /**
     * @var LanguageItems
     */
    protected $languageItem;

    protected $flexFormData = [
        'flexParentDatabaseRow' => [
            'uid' => 1,
            'pid' => 1,
        ],
        'items' => [
            [
                'Plain', // TCEforms: label
                'plain', // TCEforms: key
            ],
        ],
    ];

    protected function setUp(): void
    {
        /* @var $settingsServiceMock SettingsService|\PHPUnit_Framework_MockObject_MockObject */
        $settingsServiceMock = $this->createMock(SettingsService::class);

        /* @var $objectManagerMock ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
        $objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $objectManagerMock
            ->expects($this->any())->method('get')
            ->with(
                $this->equalTo(SettingsService::class),
                $this->equalTo(1)
            )
            ->will($this->returnValue($settingsServiceMock));

        $cacheBackendMock = new TransientMemoryBackend('Testing');
        $cacheFrontendMock = new VariableFrontend(
            'beautyofcode',
            $cacheBackendMock
        );
        /** @var CacheManager|\PHPUnit_Framework_MockObject_MockObject $cacheManagerMock */
        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock
            ->expects($this->any())
            ->method('getCache')
            ->with($this->equalTo('cache_beautyofcode'))
            ->willReturn($cacheFrontendMock);

        /* @var $highlighterConfigurationMock SyntaxHighlighter|\PHPUnit_Framework_MockObject_MockObject */
        $highlighterConfigurationMock = $this
            ->getMockBuilder(SyntaxHighlighter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('hasBrushIdentifier')
            ->will($this->returnValue(true));
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('getBrushIdentifierAliasAndLabel')
            ->will($this->returnValue(['SQL / MySQL' => 'sql']));

        $this->languageItem = new LanguageItems();
        // @extensionScannerIgnoreLine
        $this->languageItem->injectObjectManager($objectManagerMock);
        $this->languageItem->injectCacheManager($cacheManagerMock);
        $this->languageItem->injectHighlighterConfiguration($highlighterConfigurationMock);
    }

    /**
     * @test
     */
    public function configuredBrushesAreUniquelyAddedToTheReturnValue()
    {
        $newConfig = $this->languageItem->getConfiguredLanguages($this->flexFormData);

        $this->assertEquals('plain', $newConfig['items'][0][1]);
        $this->assertEquals(1, is_countable($newConfig['items']) ? count($newConfig['items']) : 0);
    }

    /**
     * @test
     */
    public function configuredBrushesAreAppendedSortedToTheReturnValue()
    {
        /* @var $settingsServiceMock SettingsService|\PHPUnit_Framework_MockObject_MockObject */
        $settingsServiceMock = $this->createMock(SettingsService::class);
        $settingsServiceMock
            ->expects($this->once())
            ->method('getTypoScriptByPath')
            ->with($this->equalTo('brushes'))
            ->willReturn('Sql, Python, Php');

        /* @var $objectManagerMock ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
        $objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $objectManagerMock
            ->expects($this->any())->method('get')
            ->with(
                $this->equalTo(SettingsService::class),
                $this->equalTo(1)
            )
            ->will($this->returnValue($settingsServiceMock));

        // @extensionScannerIgnoreLine
        $this->languageItem->injectObjectManager($objectManagerMock);

        $newConfig = $this->languageItem->getConfiguredLanguages($this->flexFormData);

        $this->assertEquals('plain', $newConfig['items'][0][1]);
    }
}
