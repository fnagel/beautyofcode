<?php

namespace TYPO3\Beautyofcode\Tests\Functional\Form\Element;

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

use TYPO3\CMS\Backend\Form\NodeFactory;

/**
 * T3editorElementTest.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class T3editorElementTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/beautyofcode'];

    /**
     * @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $nodeFactoryMock;

    /**
     * T3editorElement.
     *
     * @var \TYPO3\Beautyofcode\Form\Element\T3editorElement
     */
    protected $t3EditorElement;

    /**
     * SetUp.
     */
    public function setUp()
    {
        parent::setUp();
        $GLOBALS['TYPO3_CONF_VARS'] = [
            'SYS' => [
                'formEngine' => [
                    'nodeRegistry' => [],
                    'nodeResolver' => [],
                ],
            ],
        ];

        $this->nodeFactoryMock = $this->getMockBuilder(NodeFactory::class)->getMock();
    }

    /**
     * ItLeavesModeUntouchedIfNotBeautyofcodeContentElement.
     */
    public function testItLeavesModeUntouchedIfNotBeautyofcodeContentElement()
    {
        $this->markTestSkipped('Skipped until fixed.');

        $data = [
            'tableName' => 'tt_content',
            'databaseRow' => [
                'CType' => ['text'],
                'pi_flexform' => [
                    'data' => [
                        'sDEF' => [
                            'lDEF' => [
                                'cLang' => [
                                    'vDEF' => [
                                        'php',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $t3EditorElement = new \TYPO3\Beautyofcode\Form\Element\T3editorElement($this->nodeFactoryMock, $data);
        $t3EditorElement->setMode('mixed');

        // $this->assertSame('mixed', $t3EditorElement->getMode());
    }
}
