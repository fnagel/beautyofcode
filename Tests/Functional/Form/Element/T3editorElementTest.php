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
    protected $coreExtensionsToLoad = ['t3editor'];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/beautyofcode'];

    /**
     * @var bool
     */
    protected $resetSingletonInstances = true;

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
                'IconFactory' => [
                    'recordStatusMapping' => [
                        'hidden' => 'overlay-hidden',
                        'fe_group' => 'overlay-restricted',
                        'starttime' => 'overlay-scheduled',
                        'endtime' => 'overlay-endtime',
                        'futureendtime' => 'overlay-scheduled',
                        'readonly' => 'overlay-readonly',
                        'deleted' => 'overlay-deleted',
                        'missing' => 'overlay-missing',
                        'translated' => 'overlay-translated',
                        'protectedSection' => 'overlay-includes-subpages'
                    ],
                    'overlayPriorities' => [
                        'hidden',
                        'starttime',
                        'endtime',
                        'futureendtime',
                        'protectedSection',
                        'fe_group'
                    ]
                ]
            ],
        ];

        $this->nodeFactoryMock = $this->getMockBuilder(NodeFactory::class)->getMock();
    }

    /**
     * ItLeavesModeUntouchedIfNotBeautyofcodeContentElement.
     */
    public function testItLeavesModeUntouchedIfNotBeautyofcodeContentElement()
    {
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
            'parameterArray' => [
                'fieldConf' => [
                    'config' => []
                ]
            ],
        ];

        $t3EditorElement = new \TYPO3\Beautyofcode\Form\Element\T3editorElement($this->nodeFactoryMock, $data);
        $t3EditorElement->setMode('mixed');

        $classReflection = new \ReflectionClass($t3EditorElement);
        $methodReflection = $classReflection->getMethod('getMode');
        $methodReflection->setAccessible(true);

        $this->assertSame('mixed', $methodReflection->invoke($t3EditorElement)->getFormatCode());
    }
}
