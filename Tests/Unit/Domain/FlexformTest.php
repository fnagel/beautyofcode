<?php

namespace FelixNagel\Beautyofcode\Tests\Unit\Domain;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Highlighter\ConfigurationInterface;

/**
 * Tests the flexform domain object.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @link http://www.van-tomas.de/
 */
class FlexformTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * ConfigurationInterface.
     *
     * @var ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $highlighterConfigurationMock;

    /**
     * @var \FelixNagel\Beautyofcode\Domain\Model\Flexform
     */
    protected $flexform;

    public function setUp(): void
    {
        $this->highlighterConfigurationMock = $this->createMock(ConfigurationInterface::class);

        $this->flexform = new \FelixNagel\Beautyofcode\Domain\Model\Flexform();

        $this->flexform->injectHighlighterConfiguration($this->highlighterConfigurationMock);

        $this->flexform->setCLabel('The label');
        $this->flexform->setCLang('typoscript');
        $this->flexform->setCHighlight('1,2-3,8');
        $this->flexform->setCCollapse('1');
        $this->flexform->setCGutter('1');
    }

    /**
     * @test
     */
    public function settingAnEmptyValueForSyntaxHighlighterWillSkipTheOutputForTheSetting()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue(''));

        $this->flexform->setCCollapse('');

        $this->assertStringNotContainsString('collapse', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function settingAutoValueForSyntaxHighlighterWillSkipTheOutputForTheSetting()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue(''));

        $this->flexform->setCGutter('auto');

        $this->assertStringNotContainsString('gutter', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function highlightSettingHasSpecialFormattingForSyntaxHighlighter()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue('highlight: [1,2,3]'));

        $this->assertStringContainsString('highlight: [', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function highlightSettingWilllBeExpandedForSyntaxHighlighter()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue('highlight: [1,2,3,8]'));

        $this->assertStringContainsString('highlight: [1,2,3,8]', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function plainBrushIsAlwaysAvailableInAutoloaderBrushesStackForSyntaxHighlighter()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())->method('getAutoloaderBrushMap')
            ->will($this->returnValue(['plain' => 'Plain']));

        $brushes = $this->flexform->getAutoloaderBrushMap();

        $this->assertArrayHasKey('plain', $brushes);
    }

    /**
     * @test
     */
    public function brushesForSyntaxHighlighterAreMappedToASuitableCssTagString()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())->method('getAutoloaderBrushMap')
            ->will($this->returnValue(['typoscript' => 'Typoscript', 'actionscript3' => 'AS3']));

        $brushes = $this->flexform->getAutoloaderBrushMap();

        $this->assertArrayHasKey('typoscript', $brushes);
        $this->assertArrayHasKey('actionscript3', $brushes);
    }

    /**
     * @test
     */
    public function getIsGutterActiveReturnsFalseIfInstanceIsSetToZero()
    {
        $this->flexform->setCGutter('0');

        $this->assertFalse($this->flexform->getIsGutterActive());
    }

    /**
     * @test
     */
    public function getIsGutterActiveReturnsTrueIfInstanceIsSetToOne()
    {
        $this->flexform->setCGutter('1');

        $this->assertTrue($this->flexform->getIsGutterActive());
    }

    /**
     * @test
     */
    public function getIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsFalsy()
    {
        $this->flexform->setCGutter('auto');
        $this->flexform->setTyposcriptDefaults(['gutter' => '']);

        $this->assertFalse($this->flexform->getIsGutterActive());
    }

    /**
     * @test
     */
    public function getIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsOff()
    {
        $this->flexform->setCGutter('auto');
        $this->flexform->setTyposcriptDefaults(['gutter' => '0']);

        $this->assertFalse($this->flexform->getIsGutterActive());
    }

    /**
     * @test
     */
    public function getIsGutterActiveReturnsTrueIfInstanceIsSetToAutoAndDefaultValueIsOn()
    {
        $this->flexform->setCGutter('auto');
        $this->flexform->setTyposcriptDefaults(['gutter' => '1']);

        $this->assertTrue($this->flexform->getIsGutterActive());
    }
}
