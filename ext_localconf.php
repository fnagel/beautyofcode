<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'ContentRenderer',
	array(
		'Content' => 'render'
	),
	array(
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'PageAssets',
	array(
		'PageAssets' => 'add'
	),
	array()
);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Backend/Hooks/PageLayoutViewHooks.php:TYPO3\Beautyofcode\Backend\Hooks\PageLayoutViewHooks->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_contentrenderer'][] = $backendPreviewHook;

// registry for available syntax highlighting libraries and their brushes
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] = array(
	'SyntaxHighlighter' => array(
		'path' => 'EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/scripts/',
		'excludePattern' => 'sh(Autoloader|Core|Legacy)\.js',
		'prefix' => 'shBrush',
		'suffix' => '.js',
		'dependencies' => array(),
	),
	'Prism' => array(
		'path' => 'EXT:beautyofcode/Resources/Public/Javascript/vendor/prism/components/',
		'excludePattern' => '.*(core|extras|\.min)\.js',
		'prefix' => 'prism-',
		'suffix' => '.js',
		'dependencies' => array(
			'bash' => 'clike',
			'c' => 'clike',
			'coffeescript' => 'javascript',
			'cpp' => 'c',
			'csharp' => 'clike',
			'go' => 'clike',
			'groovy' => 'clike',
			'java' => 'clike',
			'javascript' => 'clike',
			'php' => 'clike',
			'ruby' => 'clike',
			'scss' => 'css',
			'typoscript' => 'clike',
		),
	),
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['IdentifierAliases'] = array(
	'SyntaxHighlighter' => array(
		'AppleScript' => 'applescript',
		'AS3' => 'actionscript3',
		'Bash' => 'bash',
		'ColdFusion' => 'coldfusion',
		'Cpp' => 'cpp',
		'CSharp' => 'csharp',
		'Css' => 'css',
		'Delphi' => 'delphi',
		'Diff' => 'diff',
		'Erlang' => 'erlang',
		'Groovy' => 'groovy',
		'Java' => 'java',
		'JavaFX' => 'javafx',
		'JScript' => 'javascript',
		'Perl' => 'perl',
		'Php' => 'php',
		'Plain' => 'plain',
		'PowerShell' => 'powershell',
		'Python' => 'python',
		'Ruby' => 'ruby',
		'Scala' => 'scala',
		'Sql' => 'sql',
		'Typoscript' => 'typoscript',
		'Vb' => 'vbnet',
		'Xml' => 'xml',
	),
	'Prism' => array(
		'bash' => 'bash',
		'c' => 'c',
		'clike' => 'clike',
		'coffeescript' => 'coffeescript',
		'cpp' => 'cpp',
		'csharp' => 'csharp',
		'css' => 'css',
		'gherkin' => 'gherkin',
		'go' => 'go',
		'groovy' => 'groovy',
		'http' => 'http',
		'java' => 'java',
		'javascript' => 'javascript',
		'markup' => 'markup',
		'php' => 'php',
		'plain' => 'plain',
		'python' => 'python',
		'ruby' => 'ruby',
		'scss' => 'scss',
		'sql' => 'sql',
		'typoscript' => 'typoscript',
	),
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['FailsafeAliases'] = array(
	'SyntaxHighlighter' => array(
		'Prism' => array(
			'c' => 'cpp',
			'coffeescript' => 'javascript',
			'gherkin' => 'plain',
			'go' => 'plain',
			'http' => 'plain',
			'markup' => 'xml',
			'scss' => 'sass',
		),
	),
	'Prism' => array(
		'SyntaxHighlighter' => array(
			'applescript' => 'javascript',
			'actionscript3' => 'javascript',
			'coldfusion' => 'markup',
			'delphi' => 'plain',
			'diff' => 'plain',
			'erlang' => 'plain',
			'javafx' => 'java',
			'perl' => 'c',
			'powershell' => 'bash',
			'sass' => 'scss',
			'scala' => 'java',
			'vb' => 'plain',
			'xml' => 'markup',
		),
	),
);