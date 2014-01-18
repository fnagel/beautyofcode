plugin.tx_beautyofcode.settings {

		# cat=beautyofcode/enable/1; type=boolean; label=Template - Show label: If set to false the label atg within the template is hidden.
	showLabel = 1

		# cat=beautyofcode//1; type=string; label=Library implementation: Use a fully qualified class path to use an alternative implementation.
	library = Prism

		# cat=beautyofcode//2; type=boolean; label=Resources: use minimized version?
	useMinimizedResources = 1

		# cat=beautyofcode//3; type=string; label=Loaded programming languages: Define which programming languages should be available. Less is more: every brush is loaded a single js file. Add a seperated list out of: bash, c, clike, coffeescript, cpp, csharp, css-extras, css, gherkin, go, groovy, http, java, javascript, markup, php-extras, php, python, ruby, scss, sql.
	brushes = markup,javascript,clike,csharp

		# cat=beautyofcode//4; type=string; label=Possbile themes: Coy, Dark, Funky, Okaidia, Tomorrow, Twilight. Empty string for default theme.
	theme = 

		# cat=beautyofcode//7; type=boolean; label=Gutter: Show or hide gutter. Helps user to recognize correct line with numbers.
	defaults.gutter = 1
}