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
            ->count('uid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                )
            )
            ->execute()
            ->fetchColumn(0);

        return 0 < $this->countOldPlugins;
    }

    /**
     * Executes the update script.
     *
     * @return string
     */
    public function main()
    {
        $output = 'Nothing needs to be updated.';

        if ($this->hasInstanceOldPlugins()) {
            $output = $this->updateOldPlugins();
        }

        return $output;
    }

    /**
     * Updates tt_content records by setting `list_type` to new plugin signature.
     *
     * @return string
     */
    protected function updateOldPlugins()
    {
        // switch from CType = 'list' to custom CE
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
            ->execute();

        $contentElements = $queryBuilder
            ->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'CType',
                    $queryBuilder->createNamedParameter('beautyofcode_contentrenderer')
                )
            )
            ->execute();

        while ($contentElement = $contentElements->fetch()) {
            $uid = (int)$contentElement['uid'];
            $flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($contentElement['pi_flexform']);

            try {
                $codeBlock = \TYPO3\CMS\Core\Utility\ArrayUtility::getValueByPath(
                    $flexformData,
                    'data/sDEF/lDEF/cCode/vDEF'
                );
            } catch (\Exception $exc) {
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
                ->execute();
        }

        return sprintf('<p>Updated plugin signature of %s tt_content records.</p>', $this->countOldPlugins);
    }

    protected function getQueryBuilderForTable(string $table): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\ConnectionPool::class
        )->getQueryBuilderForTable($table);
    }
}
