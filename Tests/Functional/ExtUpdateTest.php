<?php

namespace FelixNagel\Beautyofcode\Tests\Functional;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Class short description.
 *
 * Class long description
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ExtUpdateTest extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase
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

    public function setUp(): void
    {
        parent::setUp();
        $this->importDataSet('EXT:beautyofcode/Tests/Fixtures/tt_content.xml');

        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\ConnectionPool::class
        );
        $this->queryBuilder = $this->getMockBuilder(\TYPO3\CMS\Core\Database\Query\QueryBuilder::class)
            ->setConstructorArgs([$connectionPool->getConnectionForTable('tt_content')])
            ->getMock();
    }

    public function tearDown(): void
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

        $this->assertEquals('<p>Updated plugin signature of 1 tt_content records.</p>', $extensionUpdate->main());
    }
}
