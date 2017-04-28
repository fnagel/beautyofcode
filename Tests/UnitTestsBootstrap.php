<?php

namespace TYPO3\Beautyofcode\Tests;

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

/**
 * UnitTestsBootstrap.
 */
class UnitTestsBootstrap
{
    /**
     * Bootstraps the system for unit tests.
     */
    public function bootstrapSystem()
    {
        $this->defineSitePath();
        $this->finishCoreBootstrap();
    }

    /**
     * Defines the PATH_site and PATH_thisScript constant.
     */
    protected function defineSitePath()
    {
        $webRoot = $this->getWebRoot();

        if ($webRoot !== false) {
            putenv('TYPO3_PATH_ROOT=' . $webRoot);
        }
    }

    /**
     * Returns the absolute path the TYPO3 document root.
     *
     * @return string the TYPO3 document root using Unix path separators
     */
    protected function getWebRoot()
    {
        if (getenv('TYPO3_PATH_WEB')) {
            // Use environment variable
            $webRoot = getenv('TYPO3_PATH_WEB');
        } else {
            // Is there a parent TYPO3 installation?
            $webRoot = preg_replace('/typo3conf\/ext\/beautyofcode/', '', getcwd());

            if (!(file_exists($webRoot) && file_exists($webRoot.'typo3'))) {
                return false;
            }
        }

        return rtrim(strtr($webRoot, '\\', '/'), '/').'/';
    }

    /**
     * Finishes the last steps of the Core Bootstrap.
     *
     * @return UnitTestsBootstrap fluent interface
     */
    protected function finishCoreBootstrap()
    {
        /** @var \Composer\Autoload\ClassLoader $autoloader */
        $autoloader = require __DIR__.'/../vendor/autoload.php';
        $autoloader->addPsr4('TYPO3\\CMS\\Core\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/core/Classes/');
        $autoloader->addPsr4('TYPO3\\CMS\\Core\\Tests\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/core/Tests/');
        $autoloader->addPsr4('TYPO3\\CMS\\Extbase\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/extbase/Classes/');
        $autoloader->addPsr4('TYPO3\\CMS\\Fluid\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/fluid/Classes/');
        $autoloader->addPsr4('TYPO3\\CMS\\Backend\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/backend/Classes/');
        $autoloader->addPsr4('TYPO3\\CMS\\Frontend\\', __DIR__.'/../vendor/typo3/cms/typo3/sysext/frontend/Classes/');

        \FluidTYPO3\Development\Bootstrap::initialize(
            $autoloader,
            array(
                'fluid_template' => \FluidTYPO3\Development\Bootstrap::CACHE_PHP_NULL,
                'cache_core' => \FluidTYPO3\Development\Bootstrap::CACHE_PHP_NULL,
                'cache_rootline' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'cache_runtime' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'extbase_object' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'extbase_datamapfactory_datamap' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'extbase_typo3dbbackend_tablecolumns' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'extbase_typo3dbbackend_queries' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
                'l10n' => \FluidTYPO3\Development\Bootstrap::CACHE_NULL,
            )
        );
    }
}

if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only. Please check your command.');
}

$bootstrap = new UnitTestsBootstrap();
$bootstrap->bootstrapSystem();
unset($bootstrap);
