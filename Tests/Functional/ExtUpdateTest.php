<?php

namespace TYPO3\Beautyofcode\Tests\Functional;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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

    public function setUp()
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

    public function tearDown()
    {
        unset($this->queryBuilder);
    }

    /**
     * @test
     */
    public function accessReturnsTrueIfListTypesWithOldPluginSignatureWereFound()
    {
        // $this->assertCountQuery();

        $extensionUpdate = new \ext_update();

        $this->assertTrue($extensionUpdate->access());
    }

    /**
     * @test
     */
    public function mainWillUpdateTheListTypeFieldOfOldPluginContentElements()
    {
        // $this->assertCountQuery();
        // $this->assertUpdateQuery();

        $extensionUpdate = new \ext_update();

        $this->assertEquals('<p>Updated plugin signature of 1 tt_content records.</p>', $extensionUpdate->main());
    }

    protected function assertCountQuery()
    {
        $this->queryBuilder
            ->expects($this->at(0))
            ->method('count')
            ->with(
                $this->equalTo('*')
            )
            ->will($this->returnValue(1));
    }

    protected function assertUpdateQuery()
    {
        $this->queryBuilder
            ->expects($this->at(1))
            ->method('update')
            ->with(
                $this->equalTo('tt_content')
            );
        $this->queryBuilder
            ->expects($this->at(2))
            ->method('where')
            ->with(
                $this->equalTo($this->queryBuilder->expr()->eq(
                    'list_type',
                    $this->queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                ))
            );
        $this->queryBuilder
            ->expects($this->at(3))
            ->method('set')
            ->with(
                $this->equalTo('CType'),
                $this->equalTo('beautyofcode_contentrenderer')
            );
        $this->queryBuilder
            ->expects($this->at(4))
            ->method('set')
            ->with(
                $this->equalTo('list_type'),
                $this->equalTo('')
            );
    }
}
