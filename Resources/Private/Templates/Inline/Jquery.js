{namespace boc=FNagel\Beautyofcode\ViewHelpers}
;(function ($) {
	${settings.onReadyCallback}
		$.beautyOfCode.init(<![CDATA[{]]>
			<f:if condition="{settings.baseUrl}">
			baseUrl: "{boc:makeAbsolute(url: settings.baseUrl)}",
			</f:if>
			<f:if condition="{settings.scripts}">
			scripts: "{settings.scripts}",
			</f:if>
			<f:if condition="{settings.styles}">
			styles: "{settings.styles}",
			</f:if>
			<f:if condition="{settings.theme}">
			theme: "{settings.theme}",
			</f:if>
			<f:if condition="{settings.defaults}">
			defaults: <![CDATA[{]]>
				<f:render section="Defaults" arguments="{ defaults: settings.defaults }" />
			<![CDATA[}]]>,
			</f:if>
			<f:if condition="{settings.config.strings}">
			config: <![CDATA[{]]>
				<f:render section="LanguageStrings" arguments="{ strings: settings.config.strings }" />
			},
			</f:if>
			<f:if condition="{settings.selector}">
			ready: function() <![CDATA[{]]>
				$("{settings.selector} pre.code:has(code[class])").beautifyCode();
			<![CDATA[}]]>,
			</f:if>
			brushes: ["Plain"<f:render section="Brushes" />]
		<![CDATA[}]]>);
	<![CDATA[}]]>);
})(jQuery);

<f:section name="Defaults">
	<f:for each="{defaults}" as="value" key="key" iteration="i">
		<f:if condition="{i.cycle} != 1">,</f:if>"{key}": {value}
	</f:for>
</f:section>

<f:section name="LanguageStrings">
	strings: <![CDATA[{]]>
	<f:for each="{strings}" as="value" key="key">
		{key}: "{value}",
	</f:for>
	<![CDATA[}]]>
</f:section>

<f:section name="Brushes">
	<f:for each="{settings.brushes -> boc:explode(delimiter: ',', removeEmptyValues: 'TRUE')}" as="brush">, "{brush}"</f:for>
</f:section>