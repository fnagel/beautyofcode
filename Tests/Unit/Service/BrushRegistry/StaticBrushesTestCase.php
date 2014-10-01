<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Service\BrushRegistry;

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
 * @package TYPO3\Beautyofcode\Tests\Unit\Service\BrushRegistry
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class StaticBrushesTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\Beautyofcode\Service\BrushRegistryService
	 */
	protected $brushRegistryService;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManagerStub;

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
		$this->configurationManagerStub = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager')
			->getMock();

		$this->brushDiscoveryStub = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService')
			->getMock();

		$this->highlighterConfigurationStub = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Highlighter\\Configuration')
			->getMock();

		$this->brushRegistryService = new \TYPO3\Beautyofcode\Service\BrushRegistryService();
	}

	/**
	 *
	 */
	public function testThatTheBrushesStackContainsProperAliasIdentifierMap() {
		$this->injectConfigurationManager();
		$this->injectBrushDiscoveryStubAndItIsHasNoDependencies();

		$brushesWithPlainFallback = include __DIR__ . '/Fixture/UniqueStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($brushesWithPlainFallback));

		$this->injectHighlighterConfigurationAndExpectItHasStaticBrushesAndReturnsBrushAliasesForIdentifiers();

		$brushStack = $this->brushRegistryService->getBrushes();

		$this->assertArrayHasKey('bash', $brushStack);
		$this->assertContains('AS3', $brushStack);
		$this->assertArrayHasKey('plain', $brushStack);
	}

	public function testThatTheBrushesStackContainsUniqueAliases() {
		$this->injectConfigurationManager();
		$this->injectBrushDiscoveryStubAndItIsHasNoDependencies();

		$brushesWithPlainFallback = include __DIR__ . '/Fixture/DuplicateStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($brushesWithPlainFallback));

		$this->injectHighlighterConfigurationAndExpectItHasStaticBrushesAndReturnsBrushAliasesForIdentifiers();

		$brushStack = $this->brushRegistryService->getBrushes();

		$this->assertCount(5, $brushStack);
	}

	public function testThatDependenciesAreAddedBeforeAnyConfiguredBrushes() {
		$this->injectConfigurationManager();
		$this->injectBrushDiscoveryStubAndItHasDependencies();

		$brushesWithPlainFallback = include __DIR__ . '/Fixture/UniqueStaticBrushes.php';

		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will($this->returnValue($brushesWithPlainFallback));

		$this->injectHighlighterConfigurationAndExpectItHasStaticBrushesAndReturnsBrushAliasesForIdentifiers();

		$brushStack = $this->brushRegistryService->getBrushes();

		$brushStackOrdered = array(
			'javascript' => 'JScript',
			'bash' => 'Bash',
			'actionscript' => 'AS3',
			'shell' => 'Shell',
			'plain' => 'Plain'
		);

		$this->assertSame($brushStackOrdered, $brushStack);
	}

	/**
	 *
	 */
	private function injectConfigurationManager() {
		$typoScriptSetupFixture = include __DIR__ . '/Fixture/TypoScriptSetup.php';

		$this
			->configurationManagerStub
			->expects($this->once())
			->method('getConfiguration')
			->will($this->returnValue($typoScriptSetupFixture));

		$this->brushRegistryService->injectConfigurationManager($this->configurationManagerStub);
	}

	/**
	 *
	 */
	private function injectBrushDiscoveryStubAndItIsHasNoDependencies() {
		$this
			->brushDiscoveryStub
			->expects($this->once())
			->method('getDependencies')
			->will($this->returnValue(array()));

		$this->brushRegistryService->injectBrushDiscoveryService($this->brushDiscoveryStub);
	}

	private function injectBrushDiscoveryStubAndItHasDependencies() {
		$dependenciesFixture = include __DIR__ . '/Fixture/Dependencies.php';
		$this
			->brushDiscoveryStub
			->expects($this->once())
			->method('getDependencies')
			->will($this->returnValue($dependenciesFixture));

		$this->brushRegistryService->injectBrushDiscoveryService($this->brushDiscoveryStub);
	}

	/**
	 *
	 */
	private function injectHighlighterConfigurationAndExpectItHasStaticBrushesAndReturnsBrushAliasesForIdentifiers() {
		$this
			->highlighterConfigurationStub
			->expects($this->once())
			->method('hasStaticBrushes')
			->will($this->returnValue(TRUE));

		$aliasIdentifierMapFixture = include __DIR__ . '/Fixture/BrushAliasIdentifierMap.php';
		$this
			->highlighterConfigurationStub
			->method('getBrushAliasByIdentifier')
			->will($this->returnValueMap($aliasIdentifierMapFixture));

		$this->brushRegistryService->injectHighlighterConfiguration($this->highlighterConfigurationStub);
		$this->brushRegistryService->initializeObject();
	}
}