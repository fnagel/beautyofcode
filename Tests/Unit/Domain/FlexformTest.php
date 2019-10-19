<?php

namespace FelixNagel\Beautyofcode\Tests\Unit\Domain;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
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

    public function setUp()
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

        $this->assertNotContains('collapse', $this->flexform->getClassAttributeString());
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

        $this->assertNotContains('gutter', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function highlightSettingHasSpecialFormattingForSyntaxHighlighter()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue('highlight: [1,2,3]'));

        $this->assertContains('highlight: [', $this->flexform->getClassAttributeString());
    }

    /**
     * @test
     */
    public function highlightSettingWilllBeExpandedForSyntaxHighlighter()
    {
        $this->highlighterConfigurationMock
            ->expects($this->once())
            ->method('getClassAttributeString')->will($this->returnValue('highlight: [1,2,3,8]'));

        $this->assertContains('highlight: [1,2,3,8]', $this->flexform->getClassAttributeString());
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
