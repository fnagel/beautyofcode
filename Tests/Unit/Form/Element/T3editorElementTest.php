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

/**
 * T3editorElementTest
 *
 * @package TYPO3\Beautyofcode\Tests\Unit\Form\Element
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class T3editorElementTest extends UnitTestCase {

	/**
	 * @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $nodeFactoryMock;

	/**
	 * T3editorElement
	 *
	 * @var T3editorElement
	 */
	protected $t3EditorElement;

	/**
	 * SetUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->nodeFactoryMock = $this->getMock(NodeFactory::class);
	}

	/**
	 * ItLeavesModeUntouchedIfNotBeautyofcodeContentElement
	 *
	 * @return void
	 */
	public function testItLeavesModeUntouchedIfNotBeautyofcodeContentElement() {
		$data = array(
			'tableName' => 'tt_content',
			'databaseRow' => array(
				'CType' => 'text',
			),
		);

		$t3EditorElement = new T3editorElement($this->nodeFactoryMock, $data);
		$t3EditorElement->setMode('foo');

		$this->assertSame('foo', $t3EditorElement->getMode());
	}
}
