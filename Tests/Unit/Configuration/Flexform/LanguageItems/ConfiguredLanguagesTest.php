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
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    protected bool $resetSingletonInstances = true;

    /**
     * @var LanguageItems
     */
    protected $languageItem;

    protected array $flexFormData = [
        'flexParentDatabaseRow' => [
            'uid' => 1,
            'pid' => 1,
        ],
        'items' => [
            [
                'label' => 'Plain',
                'value' => 'plain',
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $cacheBackendMock = new TransientMemoryBackend('Testing');
        $cacheFrontendMock = new VariableFrontend(
            'beautyofcode',
            $cacheBackendMock
        );
        /** @var CacheManager $cacheManagerMock */
        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock
            ->expects($this->any())
            ->method('getCache')
            ->with($this->equalTo('beautyofcode'))
            ->willReturn($cacheFrontendMock);

        /* @var $highlighterConfigurationMock SyntaxHighlighter */
        $highlighterConfigurationMock = $this
            ->getMockBuilder(SyntaxHighlighter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('hasBrushIdentifier')
            ->willReturn(true);
        $highlighterConfigurationMock
            ->expects($this->any())
            ->method('getBrushIdentifierAliasAndLabel')
            ->willReturn(['sql', 'SQL / MySQL']);

        $this->languageItem = new LanguageItems();
        $this->languageItem->injectCacheManager($cacheManagerMock);
        $this->languageItem->injectHighlighterConfiguration($highlighterConfigurationMock);
    }

    /**
     * @test
     */
    public function configuredBrushesAreUniquelyAddedToTheReturnValue()
    {
        /* @var $settingsServiceMock SettingsService */
        $settingsServiceMock = $this->createMock(SettingsService::class);
        GeneralUtility::addInstance(SettingsService::class, $settingsServiceMock);

        $newConfig = $this->languageItem->getConfiguredLanguages($this->flexFormData);

        $this->assertEquals('plain', $newConfig['items'][0]['value']);
        $this->assertEquals(1, is_countable($newConfig['items']) ? count($newConfig['items']) : 0);
    }

    /**
     * @test
     */
    public function configuredBrushesAreAppendedSortedToTheReturnValue()
    {
        /* @var $settingsServiceMock SettingsService */
        $settingsServiceMock = $this->createMock(SettingsService::class);
        $settingsServiceMock
            ->expects($this->once())
            ->method('getTypoScriptByPath')
            ->with($this->equalTo('brushes'))
            ->willReturn('Sql, Python, Php');
        GeneralUtility::addInstance(SettingsService::class, $settingsServiceMock);

        $newConfig = $this->languageItem->getConfiguredLanguages($this->flexFormData);

        $this->assertEquals('plain', $newConfig['items'][0]['value']);
    }
}
