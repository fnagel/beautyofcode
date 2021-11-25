<?php

namespace FelixNagel\Beautyofcode\Tests\Functional;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * Class short description.
 *
 * Class long description
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ExtUpdateTest extends FunctionalTestCase
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
     * @var \TYPO3\CMS\Core\Database\Query\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $queryBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet('EXT:beautyofcode/Tests/Fixtures/tt_content.xml');

        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(
            ConnectionPool::class
        );
        $this->queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->setConstructorArgs([$connectionPool->getConnectionForTable('tt_content')])
            ->getMock();
    }

    protected function tearDown(): void
    {
        unset($this->queryBuilder);
    }

    /**
     * @test
     */
    public function accessReturnsTrueIfListTypesWithOldPluginSignatureWereFound()
    {
        $extensionUpdate = new \ext_update();

        $this->assertTrue($extensionUpdate->access());
    }

    /**
     * @test
     */
    public function mainWillUpdateTheListTypeFieldOfOldPluginContentElements()
    {
        $extensionUpdate = new \ext_update();

        // @extensionScannerIgnoreLine
        $this->assertEquals('<p>Updated plugin signature of 1 tt_content records.</p>', $extensionUpdate->main());
    }
}
