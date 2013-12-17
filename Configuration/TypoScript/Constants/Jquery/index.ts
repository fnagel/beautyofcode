plugin.tx_beautyofcode.settings {

		# cat=beautyofcode/enable/1; type=boolean; label=Template - Show label: If set to false the label atg within the template is hidden.
	showLabel = 1

		# cat=beautyofcode//1; type=string; label=Library implementation: Use a fully qualified class path to use an alternative implementation.
	library = Jquery

		# cat=beautyofcode/file/1; type=string; label=jQuery - Base URL: Enter path to the res directory by using EXT: or FILE: or absolute path http://your.domain.de/.../res/). Leave empty to use online repository.
	baseUrl = EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v2/

		# cat=beautyofcode/file/2; type=string; label=jQuery - Relative path to scripts: Enter relative path to baseUrl. Leave empty or default when using online repository.
	scripts = scripts/

		# cat=beautyofcode/file/3; type=string; label=jQuery - Relative path to styles: Enter relative path to baseUrl. Leave empty or default when using online repository.
	styles = styles/

		# cat=beautyofcode/enable/2; type=boolean; label=jQuery - Add jQuery Library: This option adds jQuery Framework file if the extension t3jquery is NOT installed. See documentationfor more information.
	addjQuery = 1

		# cat=beautyofcode//1; type=string; label=jQuery - jQuery selector: Define a jQuery selector to improve frontend performance (example: #myMainContent)
	selector =

		# cat=beautyofcode//2; type=string; label=onReady Callback Signature: allows alternative callback signatures (e.g. jQuery Mobile's pageshow event)
	onReadyCallback = (document).ready(function () {

		# cat=beautyofcode//3; type=string; label=Loaded programming languages: Define which programming languages should be available. Less is more: every brush is lazy loaded a single js file. Add a seperated list out of: AS3, Bash, ColdFusion, Cpp, CSharp, Css, Delphi, Diff, Erlang, Groovy, Java, JavaFX, JScript, Perl, Php, PowerShell, Python, Ruby, Scala, Typoscript, Sql, Vb, Xml.
	brushes = Xml,JScript,CSharp,Plain

		# cat=beautyofcode//4; type=string; label=Possbile themes: Midnight, RDark, Default, Django, Eclipse, Emacs, FadeToGrey, FelixNagelv3 (which is dark minimal and not available when using online hosting).
	theme = Default

		# cat=beautyofcode//5; type=int+; label=Size of tabulator: Tabulator chars will be changed to spaces. Default is 4 (spaces).
	defaults.tab-size = 4

		# cat=beautyofcode//6; type=boolean; label=Toolbar: Show toolbar when mouseover (with following functions: code in pop-up, copy to cliboard via swf, print, info; Only available in jQuery library aka SyntaxHighlighter v2).
	defaults.toolbar = 1

		# cat=beautyofcode//7; type=boolean; label=Gutter: Show or hide gutter. Helps user to recognize correct line with numbers.
	defaults.gutter = 1

		# cat=beautyofcode//8; type=boolean; label=Collapse: Allows you to force highlighted elements on the page to be collapsed. A link "show source" is displayed instead (not customizable yet).
	defaults.collapse = 0

		# cat=beautyofcode//9; type=boolean; label=Wrap lines: Allows you to turn line wrapping feature on and off. Recomended to to be enabled. Only available in jQuery library aka SyntaxHighlighter v2.
	defaults.wrap-lines = 1
}