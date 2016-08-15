<?php

namespace TYPO3\Beautyofcode\Tests\Unit\Form\Element;

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
use TYPO3\Beautyofcode\Form\Element\T3editorElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\T3editor\T3editor;

/**
 * T3editorElementTest.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class T3editorElementTest extends UnitTestCase
{
    /**
     * @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $nodeFactoryMock;

    /**
     * T3editorElement.
     *
     * @var T3editorElement
     */
    protected $t3EditorElement;

    /**
     * SetUp.
     */
    public function setUp()
    {
        $GLOBALS['TYPO3_CONF_VARS'] = array(
            'SYS' => array(
                'formEngine' => array(
                    'nodeRegistry' => array(),
                    'nodeResolver' => array(),
                ),
            ),
        );

        $this->nodeFactoryMock = $this->getMock(NodeFactory::class);
    }

    /**
     * ItLeavesModeUntouchedIfNotBeautyofcodeContentElement.
     */
    public function testItLeavesModeUntouchedIfNotBeautyofcodeContentElement()
    {
        $data = array(
            'tableName' => 'tt_content',
            'databaseRow' => array(
                'CType' => array('text'),
                'pi_flexform' => array(
                    'data' => array(
                        'sDEF' => array(
                            'lDEF' => array(
                                'cLang' => array(
                                    'vDEF' => array(
                                        'php',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );

        $t3EditorElement = new T3editorElement($this->nodeFactoryMock, $data);
        $t3EditorElement->setMode(T3editor::MODE_MIXED);

        $this->assertSame(T3editor::MODE_MIXED, $t3EditorElement->getMode());
    }
}
