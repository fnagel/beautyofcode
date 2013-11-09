{namespace boc=FNagel\Beautyofcode\ViewHelpers}

<f:if condition="{settings.includeAsDomReady} == 'jquery'">
	<f:then>
;(function ($) <![CDATA[{]]>
	${settings.onReadyCallback}
		<f:render section="Syntaxhighlighter" arguments="{_all}" />
	<![CDATA[}]]>);
<![CDATA[}]]>)(jQuery);
	</f:then>
	<f:else>
window.onDomReady = initReady;
function initReady(fn) <![CDATA[{]]>
	if (document.addEventListener) <![CDATA[{]]>
		document.addEventListener('DOMContentLoaded', fn, false);
	<![CDATA[}]]> else <![CDATA[{]]>
		document.onreadystatechange = function()<![CDATA[{]]>readyState(fn)<![CDATA[}]]>
	<![CDATA[}]]>
<![CDATA[}]]>
function readyState(func) <![CDATA[{]]>
	if (document.readyState == 'interactive' || document.readyState == 'complete') <![CDATA[{]]>
		func();
	<![CDATA[}]]>
<![CDATA[}]]>
window.onDomReady(onReady);
function onReady() <![CDATA[{]]>
	<f:render section="Syntaxhighlighter" arguments="{_all}" />
<![CDATA[}]]>;
	</f:else>
</f:if>

<f:section name="Syntaxhighlighter">
	SyntaxHighlighter.autoloader(
	<f:render section="Brushes" arguments="{ filePathBase: filePathBase, filePathScripts: filePathScripts, brushes: brushes }" />
	);
	<f:if condition="{settings.config.strings}">
	<f:render section="LanguageStrings" arguments="{ strings: settings.config.strings }" />
	</f:if>
	<f:if condition="{settings.defaults}">
	<f:render section="Defaults" arguments="{ defaults: settings.defaults }" />
	</f:if>
	SyntaxHighlighter.all();
</f:section>

<f:comment>
	defaults not available in v3: ($key != ...)
		- toolbar
</f:comment>
<f:section name="Defaults">
	<f:for each="{defaults}" key="key" as="value">
	SyntaxHighlighter.defaults['{key}'] = {value};
	</f:for>
</f:section>

<f:section name="Brushes">
		'plain	{filePathBase}{filePathScripts}shBrushPlain.js'
	<f:for each="{brushes}" key="cssTag" as="brush">
		,
		'{cssTag}	{filePathBase}{filePathScripts}shBrush{brush}.js'
	</f:for>
</f:section>

<f:comment>
	strings not available in v3: (!= && != ..)
		- viewSource
		- copyToClipboard
		- copyToClipboardConfirmation
		- print
</f:comment>
<f:section name="LanguageStrings">
	<f:for each="{strings}" key="key" as="value">
	SyntaxHighlighter.config.strings.{key} = "{value}";
	</f:for>
</f:section>