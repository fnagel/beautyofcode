<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Highlighter\BrushRegistry;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
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
 * StaticBrushesTestCase
 *
 * @package TYPO3\Beautyofcode\Tests\Unit\Highlighter\BrushRegistry
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class StaticBrushesTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\Beautyofcode\Highlighter\BrushRegistry
	 */
	protected $brushRegistry;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryStub;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $highlighterConfigurationStub;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->brushDiscoveryStub = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService')
			->getMock();

		$this->highlighterConfigurationStub = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Highlighter\\Configuration')
			->getMock();

		$this->highlighterConfigurationStub
			->expects($this->once())
			->method('hasStaticBrushes')
			->will($this->returnValue(TRUE));

		$this->brushRegistry = new \TYPO3\Beautyofcode\Highlighter\BrushRegistry(
			$this->highlighterConfigurationStub,
			array()
		);
	}

	public function testTheBrushesStackContainsProperAliasIdentifierMap() {
		$uniqueIdentifiers = include __DIR__ . '/Fixture/UniqueStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($uniqueIdentifiers));

		$this->setupIdentifierAliasMaps();

		$this->brushRegistry = new \TYPO3\Beautyofcode\Highlighter\BrushRegistry(
			$this->highlighterConfigurationStub,
			array()
		);
		$this->brushRegistry->initializeObject();

		$brushStack = $this->brushRegistry->getIterator();

		$this->assertArrayHasKey('bash', $brushStack);
		$this->assertContains('AS3', $brushStack);
		$this->assertArrayHasKey('plain', $brushStack);
	}

	public function testTheBrushesStackContainsUniqueAliases() {
		$duplicateIdentifiers = include __DIR__ . '/Fixture/DuplicateStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($duplicateIdentifiers));

		$this->setupIdentifierAliasMaps();

		$this->brushRegistry = new \TYPO3\Beautyofcode\Highlighter\BrushRegistry(
			$this->highlighterConfigurationStub,
			array()
		);
		$this->brushRegistry->initializeObject();

		$brushStack = $this->brushRegistry->getIterator();

		$this->assertCount(5, $brushStack);
	}

	public function testDependenciesAreAddedBeforeAnyConfiguredBrushes() {
		$uniqueIdentifiers = include __DIR__ . '/Fixture/UniqueStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($uniqueIdentifiers));

		$this->setupIdentifierAliasMaps();

		$dependenciesFixture = include __DIR__ . '/Fixture/Dependencies.php';

		$this->brushRegistry = new \TYPO3\Beautyofcode\Highlighter\BrushRegistry(
			$this->highlighterConfigurationStub,
			$dependenciesFixture
		);
		$this->brushRegistry->initializeObject();

		$brushStack = $this->brushRegistry->getIterator();

		$brushStackOrdered = new \ArrayIterator(
			array(
				'javascript' => 'JScript',
				'bash' => 'Bash',
				'actionscript' => 'AS3',
				'shell' => 'Shell',
				'plain' => 'Plain'
			)
		);

		$this->assertEquals($brushStackOrdered, $brushStack);
	}

	private function setupIdentifierAliasMaps() {
		$identifierAliasMapFixture = include __DIR__ . '/Fixture/BrushIdentifierAliasMap.php';
		$this
			->highlighterConfigurationStub
			->method('getBrushAliasByIdentifier')
			->will($this->returnValueMap($identifierAliasMapFixture));

		$identifierAliasMapFixture = include __DIR__ . '/Fixture/BrushAliasIdentifierMap.php';
		$this
			->highlighterConfigurationStub
			->method('getBrushIdentifierByAlias')
			->will($this->returnValueMap($identifierAliasMapFixture));
	}
}