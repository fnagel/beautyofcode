<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Felix Nagel (info@felixnagel.com)
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Plugin 'Sourcecode (beautyOfCode)' for the 'beautyofcode' extension.
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class tx_boc_div {
	var $prefixId      = 'tx_beautyofcode_pi1';		// Same as class name
	var $scriptRelPath = 'lib/class.tx_beautyofcode_div.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'beautyofcode';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * Function to solve path with FILE: and EXT: 
	 *
	 * @param	string	path to directory
	 * @return	string
	 */
	public function makeAbsolutePath($dir) {
		if (t3lib_div::isFirstPartOfStr($dir, 'EXT:'))	{
			list($extKey,$script)=explode('/',substr($dir,4),2);
			if ($extKey && t3lib_extMgm::isLoaded($extKey))	{
				$extPath=t3lib_extMgm::extPath($extKey);
				return substr($extPath,strlen(PATH_site)).$script;
			}
		} elseif (t3lib_div::isFirstPartOfStr($dir, 'FILE:')) {
				return substr($dir,5);					
		} else {
			return $dir;
		}	
	}	
	
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_div.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_div.php']);
}

?>