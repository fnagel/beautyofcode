{namespace boc=FNagel\Beautyofcode\ViewHelpers}
<f:if condition="{jQueryNoConflict}">
	{jQvar}.noConflict();
</f:if>
{jQvar}{settings.jQueryOnReadyCallback}
	{jQvar}.beautyOfCode.init(<![CDATA[{]]>
<f:if condition="{settings.baseUrl}">
		baseUrl: "{boc:makeAbsolute(url: {settings.baseUrl})}",
</f:if>
<f:if condition="{settings.jquery.scripts}">
		scripts: "{settings.jquery.scripts}",
</f:if>
<f:if condition="{settings.jquery.styles}">
		styles: "{settings.jquery.styles}",
</f:if>
<f:if condition="{settings.theme}">
		theme: "{settings.theme}",
</f:if>
		defaults: <![CDATA[{]]><f:render section="Defaults" /><![CDATA[}]]>,
		config: <![CDATA[{]]> <f:render section="LanguageStrings" />
				},
<f:if condition="{jQuerySelector}">
		ready: function() <![CDATA[{]]>
			{jQvar}("{settings.jQuery.selector} pre.code:has(code[class])").beautifyCode();
		<![CDATA[}]]>,
</f:if>
		brushes: ["Plain"<f:render section="Brushes" />]
	<![CDATA[}]]>);
<![CDATA[}]]>);

<f:section name="Defaults">
	<f:if condition="{settings.defaults}">
		<f:for each="{settings.defaults}" as="value" key="key" iteration="i">
<f:if condition="{i.cycle} != 1">,</f:if>"{key}": {value}
		</f:for>
	</f:if>
</f:section>

<f:section name="LanguageStrings">
	<f:if condition="{settings.config.strings}">

			strings: {
		<f:for each="{settings.config.strings}" as="value" key="key">
				{key}: "{value}",
		</f:for>
			}
	</f:if>
</f:section>

<f:section name="Brushes">
	<f:comment>
		<f:for each="{settings.brushes -> f:format.explode(delimiter: ',', removeEmptyValues: 'TRUE')}" as="brush">
	,"{brush}";
		</f:for>
	</f:comment>
</f:section>