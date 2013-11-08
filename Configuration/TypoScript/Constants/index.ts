plugin.tx_beautyofcode {

	// -- ENABLE FEATURES
	# cat=beautyofcode/enable/1; type=options [standalone=standalone,jquery=jquery]; label=Coose which version to use: jQuery (SyntaxHighlighter v2) or standalone (SyntaxHighlighter v3)
	version = standalone

		# cat=beautyofcode/file/020; type=boolean; label=Template - Show label: If set to false the label atg within the template is hidden.
	showLabel = 1

	// -- VERSION DEPENDENT SETTINGS
		# cat=beautyofcode/file/030; type=string; label=Standalone - Base URL: Enter path to the res directory by using EXT: or FILE: or absolute path http://your.domain.de/.../res/). Leave empty to use online repository.
	standalone.baseUrl = EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v3/
		# cat=beautyofcode/file/031; type=string; label=Standalone - Relative path to scripts: Enter relative path to baseUrl. Leave empty or default when using online repository.
	standalone.scripts = scripts/
		# cat=beautyofcode/file/032; type=string; label=Standalone - Relative path to styles: Enter relative path to baseUrl. Leave empty or default when using online repository.
	standalone.styles = styles/
		# cat=beautyofcode/file/034; type=options [standalone=standalone,jquery=jquery]; label=Standalone - Use JS dom ready event: If using standalone version it's possible to add a JS domReady instaed of injecting the code at the bottom of the body. Usefull when using minifaction scripts.
	standalone.includeAsDomReady = false
		# cat=beautyofcode/file/035; type=string; label=onReady callback signature: allows alternative callback signatures (e.g. jQuery Mobile's pageshow event)
	standalone.onReadyCallback = (document).ready(function () {

		# cat=beautyofcode/file/040; type=string; label=jQuery - Base URL: Enter path to the res directory by using EXT: or FILE: or absolute path http://your.domain.de/.../res/). Leave empty to use online repository.
	jquery.baseUrl = EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v2/
		# cat=beautyofcode/file/041; type=string; label=jQuery - Relative path to scripts: Enter relative path to baseUrl. Leave empty or default when using online repository.
	jquery.scripts = scripts/
		# cat=beautyofcode/file/042; type=string; label=jQuery - Relative path to styles: Enter relative path to baseUrl. Leave empty or default when using online repository.
	jquery.styles = styles/
		# cat=beautyofcode/file/043; type=string; label=jQuery - Script URL: Path to jquery.beautyOfCode.js file (use FILE: and EXT: or absolute path http://your.domain.de/...).
	jquery.scriptUrl = EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery.beautyOfCode.js
		# cat=beautyofcode/file/044; type=boolean; label=jQuery - Add jQuery Library: This option adds jQuery Framework file if the extension t3jquery is NOT installed. See documentationfor more information.
	jquery.addjQuery = 1
		# cat=beautyofcode/file/045; type=string; label=jQuery - jQuery selector: Define a jQuery selector to improve frontend performance (example: #myMainContent)
	jquery.selector =
		# cat=beautyofcode//9; type=string; label=onReady Callback Signature: allows alternative callback signatures (e.g. jQuery Mobile's pageshow event)
	jquery.onReadyCallback = (document).ready(function () {

	// -- COMMON SETTINGS
		# cat=beautyofcode//1; type=string; label=Loaded programming languages: Define which programming languages should be available. Less is more: every brush is lazy loaded a single js file. Add a seperated list out of: AS3, Bash, ColdFusion, Cpp, CSharp, Css, Delphi, Diff, Erlang, Groovy, Java, JavaFX, JScript, Perl, Php, PowerShell, Python, Ruby, Scala, Typoscript, Sql, Vb, Xml.
	common.brushes = Xml,JScript,CSharp,Plain

		# cat=beautyofcode//2; type=string; label=Possbile themes: Midnight, RDark, Default, Django, Eclipse, Emacs, FadeToGrey, FelixNagelv3 (which is dark minimal and not available when using online hosting).
	common.theme = Default

		# cat=beautyofcode//4; type=int+; label=Size of tabulator: Tabulator chars will be changed to spaces. Default is 4 (spaces).
	common.defaults.tab-size = 4

		# cat=beautyofcode//5; type=boolean; label=Toolbar: Show toolbar when mouseover (with following functions: code in pop-up, copy to cliboard via swf, print, info; Only available in jQuery version aka SyntaxHighlighter v2).
	common.defaults.toolbar = 1

		# cat=beautyofcode//6; type=boolean; label=Gutter: Show or hide gutter. Helps user to recognize correct line with numbers.
	common.defaults.gutter = 1

		# cat=beautyofcode//7; type=boolean; label=Collapse: Allows you to force highlighted elements on the page to be collapsed. A link "show source" is displayed instead (not customizable yet).
	common.defaults.collapse = 0

		# cat=beautyofcode//8; type=boolean; label=Wrap lines: Allows you to turn line wrapping feature on and off. Recomended to to be enabled. Only available in jQuery version aka SyntaxHighlighter v2.
	common.defaults.wrap-lines = 1
}