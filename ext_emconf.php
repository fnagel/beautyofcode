<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "beautyofcode".
 *
 * Auto generated 11-03-2013 21:38
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'beautyOfCode Syntax Highlighter',
	'description' => 'This plugin provides Java-Script based, state-of-the-art, feature rich syntax highlighting by using SyntaxHighlighter (v2 or v3) by Alex Gorbatchev. t3editor enabled.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Felix Nagel',
	'author_email' => 'info@felixnagel.com',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-6.0.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			't3editor' => '',
		),
	),
	'_md5_values_when_last_written' => 'a:124:{s:9:"ChangeLog";s:4:"d6bf";s:21:"ext_conf_template.txt";s:4:"e651";s:12:"ext_icon.gif";s:4:"1706";s:17:"ext_localconf.php";s:4:"fb7c";s:14:"ext_tables.php";s:4:"ee61";s:16:"locallang_db.xml";s:4:"7514";s:12:"t3jquery.txt";s:4:"a6b5";s:14:"doc/manual.sxw";s:4:"ee61";s:39:"lib/class.tx_beautyofcode_addFields.php";s:4:"7458";s:33:"lib/class.tx_beautyofcode_div.php";s:4:"c517";s:36:"lib/class.tx_beautyofcode_jquery.php";s:4:"09e6";s:40:"lib/class.tx_beautyofcode_standalone.php";s:4:"abc5";s:38:"lib/class.tx_beautyofcode_t3editor.php";s:4:"894f";s:14:"pi1/ce_wiz.gif";s:4:"cf4c";s:33:"pi1/class.tx_beautyofcode_pi1.php";s:4:"abde";s:44:"pi1/class.tx_beautyofcode_pi1_cms_layout.php";s:4:"b869";s:41:"pi1/class.tx_beautyofcode_pi1_wizicon.php";s:4:"a9cb";s:23:"pi1/flexform_ds_pi1.xml";s:4:"b975";s:20:"pi1/locallang_db.xml";s:4:"3e3c";s:24:"pi1/static/constants.txt";s:4:"cf91";s:20:"pi1/static/setup.txt";s:4:"3c23";s:17:"res/template.html";s:4:"519b";s:30:"res/jquery/jquery-1.3.2.min.js";s:4:"49d4";s:33:"res/jquery/jquery.beautyOfCode.js";s:4:"ac48";s:36:"res/syntax_highlighter/v2/LGPLv3.txt";s:4:"79da";s:35:"res/syntax_highlighter/v2/test.html";s:4:"1940";s:47:"res/syntax_highlighter/v2/scripts/clipboard.swf";s:4:"e971";s:47:"res/syntax_highlighter/v2/scripts/shBrushAS3.js";s:4:"1256";s:48:"res/syntax_highlighter/v2/scripts/shBrushBash.js";s:4:"d47e";s:54:"res/syntax_highlighter/v2/scripts/shBrushColdFusion.js";s:4:"b007";s:47:"res/syntax_highlighter/v2/scripts/shBrushCpp.js";s:4:"24cc";s:50:"res/syntax_highlighter/v2/scripts/shBrushCSharp.js";s:4:"ca1c";s:47:"res/syntax_highlighter/v2/scripts/shBrushCss.js";s:4:"d40c";s:50:"res/syntax_highlighter/v2/scripts/shBrushDelphi.js";s:4:"a7b6";s:48:"res/syntax_highlighter/v2/scripts/shBrushDiff.js";s:4:"029e";s:50:"res/syntax_highlighter/v2/scripts/shBrushErlang.js";s:4:"eb1e";s:50:"res/syntax_highlighter/v2/scripts/shBrushGroovy.js";s:4:"f322";s:48:"res/syntax_highlighter/v2/scripts/shBrushJava.js";s:4:"0fe3";s:50:"res/syntax_highlighter/v2/scripts/shBrushJavaFX.js";s:4:"8682";s:51:"res/syntax_highlighter/v2/scripts/shBrushJScript.js";s:4:"3634";s:48:"res/syntax_highlighter/v2/scripts/shBrushPerl.js";s:4:"3e9a";s:47:"res/syntax_highlighter/v2/scripts/shBrushPhp.js";s:4:"aa01";s:49:"res/syntax_highlighter/v2/scripts/shBrushPlain.js";s:4:"caf1";s:54:"res/syntax_highlighter/v2/scripts/shBrushPowerShell.js";s:4:"668e";s:50:"res/syntax_highlighter/v2/scripts/shBrushPython.js";s:4:"fe82";s:48:"res/syntax_highlighter/v2/scripts/shBrushRuby.js";s:4:"1080";s:49:"res/syntax_highlighter/v2/scripts/shBrushScala.js";s:4:"ae13";s:47:"res/syntax_highlighter/v2/scripts/shBrushSql.js";s:4:"7a7f";s:54:"res/syntax_highlighter/v2/scripts/shBrushTyposcript.js";s:4:"f0da";s:46:"res/syntax_highlighter/v2/scripts/shBrushVb.js";s:4:"5db1";s:47:"res/syntax_highlighter/v2/scripts/shBrushXml.js";s:4:"b8af";s:43:"res/syntax_highlighter/v2/scripts/shCore.js";s:4:"67cf";s:45:"res/syntax_highlighter/v2/scripts/shLegacy.js";s:4:"3928";s:39:"res/syntax_highlighter/v2/src/shCore.js";s:4:"6fe1";s:41:"res/syntax_highlighter/v2/src/shLegacy.js";s:4:"c66d";s:41:"res/syntax_highlighter/v2/styles/help.png";s:4:"c381";s:46:"res/syntax_highlighter/v2/styles/magnifier.png";s:4:"a81f";s:52:"res/syntax_highlighter/v2/styles/page_white_code.png";s:4:"c65f";s:52:"res/syntax_highlighter/v2/styles/page_white_copy.png";s:4:"38de";s:44:"res/syntax_highlighter/v2/styles/printer.png";s:4:"2424";s:43:"res/syntax_highlighter/v2/styles/shCore.css";s:4:"bf2f";s:51:"res/syntax_highlighter/v2/styles/shThemeDefault.css";s:4:"ede7";s:50:"res/syntax_highlighter/v2/styles/shThemeDjango.css";s:4:"18ff";s:51:"res/syntax_highlighter/v2/styles/shThemeEclipse.css";s:4:"9d8e";s:49:"res/syntax_highlighter/v2/styles/shThemeEmacs.css";s:4:"3efb";s:54:"res/syntax_highlighter/v2/styles/shThemeFadeToGrey.css";s:4:"a2b3";s:56:"res/syntax_highlighter/v2/styles/shThemeFelixNagelv3.css";s:4:"01c2";s:52:"res/syntax_highlighter/v2/styles/shThemeMidnight.css";s:4:"e901";s:49:"res/syntax_highlighter/v2/styles/shThemeRDark.css";s:4:"b791";s:42:"res/syntax_highlighter/v2/styles/Thumbs.db";s:4:"3fb8";s:45:"res/syntax_highlighter/v2/styles/wrapping.png";s:4:"9a4f";s:36:"res/syntax_highlighter/v3/index.html";s:4:"833b";s:38:"res/syntax_highlighter/v3/LGPL-LICENSE";s:4:"79da";s:37:"res/syntax_highlighter/v3/MIT-LICENSE";s:4:"1a64";s:49:"res/syntax_highlighter/v3/scripts/shAutoloader.js";s:4:"a122";s:55:"res/syntax_highlighter/v3/scripts/shBrushAppleScript.js";s:4:"74a7";s:47:"res/syntax_highlighter/v3/scripts/shBrushAS3.js";s:4:"442d";s:48:"res/syntax_highlighter/v3/scripts/shBrushBash.js";s:4:"2d78";s:54:"res/syntax_highlighter/v3/scripts/shBrushColdFusion.js";s:4:"9158";s:47:"res/syntax_highlighter/v3/scripts/shBrushCpp.js";s:4:"f88b";s:50:"res/syntax_highlighter/v3/scripts/shBrushCSharp.js";s:4:"b280";s:47:"res/syntax_highlighter/v3/scripts/shBrushCss.js";s:4:"a07a";s:50:"res/syntax_highlighter/v3/scripts/shBrushDelphi.js";s:4:"29db";s:48:"res/syntax_highlighter/v3/scripts/shBrushDiff.js";s:4:"2e12";s:50:"res/syntax_highlighter/v3/scripts/shBrushErlang.js";s:4:"112d";s:50:"res/syntax_highlighter/v3/scripts/shBrushGroovy.js";s:4:"9c6e";s:48:"res/syntax_highlighter/v3/scripts/shBrushJava.js";s:4:"c374";s:50:"res/syntax_highlighter/v3/scripts/shBrushJavaFX.js";s:4:"0afa";s:51:"res/syntax_highlighter/v3/scripts/shBrushJScript.js";s:4:"cdae";s:48:"res/syntax_highlighter/v3/scripts/shBrushPerl.js";s:4:"b530";s:47:"res/syntax_highlighter/v3/scripts/shBrushPhp.js";s:4:"0a52";s:49:"res/syntax_highlighter/v3/scripts/shBrushPlain.js";s:4:"87fd";s:54:"res/syntax_highlighter/v3/scripts/shBrushPowerShell.js";s:4:"3939";s:50:"res/syntax_highlighter/v3/scripts/shBrushPython.js";s:4:"734f";s:48:"res/syntax_highlighter/v3/scripts/shBrushRuby.js";s:4:"8da6";s:48:"res/syntax_highlighter/v3/scripts/shBrushSass.js";s:4:"ebb4";s:49:"res/syntax_highlighter/v3/scripts/shBrushScala.js";s:4:"ec43";s:47:"res/syntax_highlighter/v3/scripts/shBrushSql.js";s:4:"3de8";s:54:"res/syntax_highlighter/v3/scripts/shBrushTyposcript.js";s:4:"54c3";s:46:"res/syntax_highlighter/v3/scripts/shBrushVb.js";s:4:"bb8a";s:47:"res/syntax_highlighter/v3/scripts/shBrushXml.js";s:4:"ba29";s:43:"res/syntax_highlighter/v3/scripts/shCore.js";s:4:"488c";s:45:"res/syntax_highlighter/v3/scripts/shLegacy.js";s:4:"b37b";s:45:"res/syntax_highlighter/v3/src/shAutoloader.js";s:4:"5672";s:39:"res/syntax_highlighter/v3/src/shCore.js";s:4:"d70a";s:41:"res/syntax_highlighter/v3/src/shLegacy.js";s:4:"dcf9";s:43:"res/syntax_highlighter/v3/styles/shCore.css";s:4:"604c";s:50:"res/syntax_highlighter/v3/styles/shCoreDefault.css";s:4:"59ac";s:49:"res/syntax_highlighter/v3/styles/shCoreDjango.css";s:4:"da28";s:50:"res/syntax_highlighter/v3/styles/shCoreEclipse.css";s:4:"e81b";s:48:"res/syntax_highlighter/v3/styles/shCoreEmacs.css";s:4:"c8f7";s:53:"res/syntax_highlighter/v3/styles/shCoreFadeToGrey.css";s:4:"b9ba";s:50:"res/syntax_highlighter/v3/styles/shCoreMDUltra.css";s:4:"c93e";s:51:"res/syntax_highlighter/v3/styles/shCoreMidnight.css";s:4:"d3e8";s:48:"res/syntax_highlighter/v3/styles/shCoreRDark.css";s:4:"6a70";s:51:"res/syntax_highlighter/v3/styles/shThemeDefault.css";s:4:"d4d5";s:50:"res/syntax_highlighter/v3/styles/shThemeDjango.css";s:4:"6a66";s:51:"res/syntax_highlighter/v3/styles/shThemeEclipse.css";s:4:"9fc3";s:49:"res/syntax_highlighter/v3/styles/shThemeEmacs.css";s:4:"f8f5";s:54:"res/syntax_highlighter/v3/styles/shThemeFadeToGrey.css";s:4:"f712";s:56:"res/syntax_highlighter/v3/styles/shThemeFelixNagelv3.css";s:4:"57aa";s:51:"res/syntax_highlighter/v3/styles/shThemeMDUltra.css";s:4:"06d9";s:52:"res/syntax_highlighter/v3/styles/shThemeMidnight.css";s:4:"541a";s:49:"res/syntax_highlighter/v3/styles/shThemeRDark.css";s:4:"f830";}',
	'suggests' => array(
	),
);

?>