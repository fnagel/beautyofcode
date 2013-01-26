<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_beautyofcode_pi1.php','_pi1','list_type',1);
include_once(t3lib_extMgm::extPath($_EXTKEY).'lib/class.tx_beautyofcode_addFields.php');

// BE preview
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_pi1'][] = 'EXT:beautyofcode/pi1/class.tx_beautyofcode_pi1_cms_layout.php:tx_beautyofcode_cms_layout->getExtensionSummary';
?>