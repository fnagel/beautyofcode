<?php

namespace FelixNagel\Beautyofcode\Tests\Functional\Form\Element;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use ReflectionClass;
use FelixNagel\Beautyofcode\Form\Element\T3editorElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * T3editorElementTest.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class T3editorElementTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = ['backend'];

    protected array $testExtensionsToLoad = ['typo3conf/ext/beautyofcode'];

    /**
     * @var NodeFactory
     */
    protected $nodeFactoryMock;

    /**
     * SetUp.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->nodeFactoryMock = $this->getMockBuilder(NodeFactory::class)->getMock();
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [
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
                    'config' => [],
                ],
            ],
        ];
    }

    public function testItChangesConfigIfBeautyofcodeContentElement()
    {
        $data = $this->getDefaultData();
        $data['databaseRow']['CType'] = ['beautyofcode_contentrenderer'];

        $t3EditorElement = new T3editorElement($this->nodeFactoryMock, $data);

        $classReflection = new ReflectionClass($t3EditorElement);
        $methodReflection = $classReflection->getMethod('determineMode');
        $methodReflection->setAccessible(true);

        $this->assertSame('php', $methodReflection->invoke($t3EditorElement));
    }

    public function testItLeavesConfigUntouchedIfNotBeautyofcodeContentElement()
    {
        $data = $this->getDefaultData();

        $t3EditorElement = new T3editorElement($this->nodeFactoryMock, $data);

        $classReflection = new ReflectionClass($t3EditorElement);
        $methodReflection = $classReflection->getMethod('determineMode');
        $methodReflection->setAccessible(true);

        $this->assertSame(T3editorElement::T3EDITOR_MODE_DEFAULT, $methodReflection->invoke($t3EditorElement));
    }

    public function testItLeavesTcaConfigUntouchedIfNotBeautyofcodeContentElement()
    {
        $this->markTestSkipped('Does not work due to wrong init of ModeRegistry::getInstance()->getDefaultMode()');

        $data = $this->getDefaultData();
        $data['parameterArray']['fieldConf']['config']['format'] = 'css';

        $t3EditorElement = new T3editorElement($this->nodeFactoryMock, $data);
        $t3EditorElement->render();

        $classReflection = new ReflectionClass($t3EditorElement);
        $propertyReflection = $classReflection->getProperty('data');
        $propertyReflection->setAccessible(true);

        $this->assertSame('css', $propertyReflection->getValue($t3EditorElement)['parameterArray']['fieldConf']['config']['format']);
    }
}
