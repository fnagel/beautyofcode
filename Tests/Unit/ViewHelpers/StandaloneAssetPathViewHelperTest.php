<?php

namespace FelixNagel\Beautyofcode\Tests\Unit\ViewHelpers;

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

/**
 * Tests the standalone asset path view helper.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @link http://www.van-tomas.de/
 */
class StandaloneAssetPathViewHelperTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     * @expectedException \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @expectedExceptionMessage The type argument must be one of scripts, styles.
     */
    public function exceptionIsThrownDuringInitializationIfInvalidTypeIsSet()
    {
        $viewHelper = new \FelixNagel\Beautyofcode\ViewHelpers\StandaloneAssetPathViewHelper();
        $viewHelper->setArguments(['baseUrl' => '', 'resourcePath' => '', 'type' => 'foobar']);
        $viewHelper->initializeArguments();
        $viewHelper->initialize();
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function returnsTheDefaultPathForScriptsIfNoBaseUrlAndNoScriptsResourcePathIsSet()
    {
        $viewHelper = new \FelixNagel\Beautyofcode\ViewHelpers\StandaloneAssetPathViewHelper();
        $viewHelper->setArguments(['baseUrl' => '', 'resourcePath' => '', 'type' => 'scripts']);
        $viewHelper->initializeArguments();
        $viewHelper->initialize();
        $path = $viewHelper->render();

        $this->assertEquals('http://alexgorbatchev.com/pub/sh/current/scripts/', $path);
    }

    /**
     * @test
     */
    public function returnsTheDefaultPathForStylesIfNoBaseUrlAndNoStylesResourcePathIsSet()
    {
        $viewHelper = new \FelixNagel\Beautyofcode\ViewHelpers\StandaloneAssetPathViewHelper();
        $viewHelper->setArguments(['baseUrl' => '', 'resourcePath' => '', 'type' => 'styles']);
        $viewHelper->initializeArguments();
        $viewHelper->initialize();
        $path = $viewHelper->render();

        $this->assertEquals('http://alexgorbatchev.com/pub/sh/current/styles/', $path);
    }

    /**
     * @test
     */
    public function returnsTheExpectedScriptResourcePathIfBaseUrlAndResourcePathAreSet()
    {
        $viewHelper = new \FelixNagel\Beautyofcode\ViewHelpers\StandaloneAssetPathViewHelper();
        $viewHelper->setArguments([
            'baseUrl' => '/typo3conf/ext/beautyofcode/',
            'resourcePath' => 'Resources/Public/Javascript/vendor/syntax_highlighter/v3/scripts/',
            'type' => 'scripts'
        ]);
        $viewHelper->initializeArguments();
        $viewHelper->initialize();
        $path = $viewHelper->render();

        $this->assertEquals(
            '/typo3conf/ext/beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v3/scripts/',
            $path
        );
    }
}
