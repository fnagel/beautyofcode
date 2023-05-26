<?php

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Updates tt_content records.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ext_update
{
    /**
     * Amount of old plugins detected.
     *
     * @var int
     */
    protected $countOldPlugins;

    /**
     * Checks if the update script must be run.
     *
     * @return bool
     */
    public function access()
    {
        return $this->hasInstanceOldPlugins();
    }

    /**
     * Counts the amount of old plugin instances within tt_content records.
     *
     * @return bool
     */
    protected function hasInstanceOldPlugins()
    {
        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $this->countOldPlugins = $queryBuilder
            ->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'CType',
                    $queryBuilder->createNamedParameter('list')
                ),
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                )
            )
            ->executeQuery()
            ->fetchOne();

        return $this->countOldPlugins > 0;
    }

    /**
     * Executes the update script.
     *
     * @return string
     */
    public function main()
    {
        $output = '<p>Nothing needs to be updated.</p>';

        if ($this->hasInstanceOldPlugins()) {
            $output = $this->updateOldPlugins();
        }

        return $output;
    }

    /**
     * Updates tt_content records by setting `list_type` to new plugin signature.
     */
    protected function updateOldPlugins(): string
    {
        // Switch from CType = 'list' to custom CE
        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $queryBuilder
            ->update('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                )
            )
            ->set('CType', 'beautyofcode_contentrenderer')
            ->set('list_type', '')
            ->executeStatement();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $contentElements = $queryBuilder->select(['uid', 'pi_flexform'])
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'CType',
                    $queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                )
            )
            ->executeQuery();

        while ($contentElement = $contentElements->fetchAssociative()) {
            $uid = (int) $contentElement['uid'];
            $flexformData = GeneralUtility::xml2array($contentElement['pi_flexform']);

            try {
                $codeBlock = ArrayUtility::getValueByPath(
                    $flexformData,
                    'data/sDEF/lDEF/cCode/vDEF'
                );
            } catch (\Exception $exception) {
                continue;
            }

            $queryBuilder = $this->getQueryBuilderForTable('tt_content');
            $queryBuilder
                ->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
                    )
                )
                ->set('bodytext', $codeBlock)
                ->executeStatement();
        }

        return sprintf('<p>Updated plugin signature of %s tt_content records.</p>', $this->countOldPlugins);
    }

    protected function getQueryBuilderForTable(string $table): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }
}
