<?php

namespace FelixNagel\Beautyofcode\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use FelixNagel\Beautyofcode\ViewHelpers\StandaloneAssetPathViewHelper;

/**
 * Tests the standalone asset path view helper.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 *
 * @link http://www.van-tomas.de/
 */
class StandaloneAssetPathViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function exceptionIsThrownDuringInitializationIfInvalidTypeIsSet()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The type argument must be one of scripts, styles.');

        $viewHelper = new StandaloneAssetPathViewHelper();
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
        $viewHelper = new StandaloneAssetPathViewHelper();
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
        $viewHelper = new StandaloneAssetPathViewHelper();
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
        $viewHelper = new StandaloneAssetPathViewHelper();
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
