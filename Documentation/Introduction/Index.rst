.. include:: ../Includes.txt

.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

This plugin provides state-of-the-art syntax highlighting by using Java-Script 
by using  SyntaxHighlighting 2.0 by Alex Gorbatchev.

This is done by using the jQuery plugin beautyOfCode (jQuery version, 
SyntaxHighlighter v2) or without using jQuery (standalone version) by using new 
autoloader feature introduced with SyntaxHighlighter v3.

Since version 2.0 the `prism syntax highlighter`_ written by Lea Verou is 
included.

Use the constant editor or TS to define needed programming languages. These 
will be available in the FE plugin which needs to be added as a generic FE 
plugin (“insert plugin”). Each of this languages is a lazy-loaded (after the 
page has finished loading) Java-Script file. You can define different css 
styles (so called themes) and HTML templates to match your page design.

The following programming languages are available: Actionscript 3, Bash, Shell, 
ColdFusion", C, C++, C#, CSS, Delphi, Pas, Pascal, Diff, Patch, Erlang, Groovy, 
Java, Java FX, Java-Script, Perl, PHP, Power-Shell, Python, Ruby on Rails, 
Scala, SQL, Typoscript, MySQL, Virtual Basic, .Net, XML, XSLT, XHTML and HTML. 
There are even more available, please check out `The syntax highlighter online 
help for brushes <SyntaxhighlighterOnlineHelpBrushes_>`_.

The newly added TypoScript brush is based upon a `user language for Notepad++ 
<TypoScriptUserLanguageNotepadPP_>`_.

Note, that this list varies dependent on the selected static template/library. 
A list of available languages for `prism is available here 
<ListOfAvailablePrismLanguages_>`_.

**Note #1**: This extension currently needs jQuery Java-Script framework (see 
Installation). I'm working in a non jQuery option, see ToDo.

**Note #2**: Since version 0.7.0 its possible to use this extension without 
jQuery.

.. _features:

Features
--------

- Highlight specific lines
- Show Gutter
- Customizable tab size \*\*
- Configurable label
- Collapse code \*\*
- Wrap lines \*\*
- Preview and t3editor support in BE
- Use HTML templating
- Different visual styles
- Configurable JS language  strings in FE \*
- Show Toolbar with \*

  - Print in FE view
  - Copy to directly to clipboard (via swf) in FE view
  - Copy manually out of an pop-up in FE view

\* if using jQuery version aka Syntax Highlighter v2

\*\* unavailable if using prism

.. _live-demo:

Live Demo
---------

`ext:beautyofcode Live Demo <LiveDemo_>`_

*Please help me to add more sites here:* `contact me <LiveDemoContact_>`_!

Syntax Highlighter 2.0 and v3
-----------------------------

Syntax Highlighter 2.0 by Alex Gorbatchev, which does the actually 
highlighting, is the nicest syntax highlighter I've seen so far and it's also 
used by Freshbooks, ASP .Net Forums, wordpress.com, Aptana, Mozilla Developer 
Center, SitePoint and Yahoo Developer Network. It is coded in native 
Java-Script and is under active development.

Please see http://alexgorbatchev.com/wiki/SyntaxHighlighter for more information.

jQuery Plugin beautyOfCode
--------------------------

Beauty of Code is a jQuery plugin written by Lars Corneliussen. This extension 
uses beautyOfCode to implement SyntaxHghlighter 2.0 in a xhtml 1.0 compliant 
way (see above) and to add the desired js files by `lazy loading <LazyLoading_>`_.

Read this article by `Lars Corneliussen`_ for more information.

Prism
-----

Prism is a lightweight, extensible syntax highlighter, built with modern web 
standards in mind. It's a spin-off from Dabblet and is tested there daily by 
thousands.

See `http://prismjs.com/ <prism syntax highlighter_>`_ for more information.

.. _screenshots:

Screenshots
-----------

.. figure:: ../Images/Screenshots/FrontendPlugin.jpg
      :width: 500px
      :alt: Frontend Plugin

      Frontend Plugin

      This is the default theme when using jQuery version.

.. figure:: ../Images/Screenshots/ConstantEditorSettings.jpg
      :width: 500px
      :alt: Constant Editor Settings

      Constant Editor Settings

      Some of the available constant editor settings

.. figure:: ../Images/Screenshots/BackendFCE01.jpg
      :width: 500px
      :alt: Backend FCE: Standard Tab

      Backend FCE: Standard Tab

      This screenshot shows the standard tab where you insert the code block 
      label, syntax language and the code block itself.

.. figure:: ../Images/Screenshots/BackendFCE02.jpg
      :width: 500px
      :alt: Backend FCE: Overwrite Configuration Tab

      Backend FCE: Overwrite Configuration Tab

      This screenshot shows the options tab of the backend FCE which allows you 
      to overwrite some of the default configuration options set via TypoScript.

.. _prism syntax highlighter: http://prismjs.com/
.. _SyntaxhighlighterOnlineHelpBrushes: http://alexgorbatchev.com/wiki/SyntaxHighlighter:Brushes
.. _ListOfAvailablePrismLanguages: https://github.com/LeaVerou/prism/tree/gh-pages/components
.. _TypoScriptUserLanguageNotepadPP: http://sourceforge.net/tracker/?func=detail&aid=2839067&group_id=95717&atid=612385
.. _LiveDemo: http://FelixNagel.com/Blog/
.. _LiveDemoContact: http://www.felixnagel.com/contact
.. _LazyLoading: http://en.wikipedia.org/wiki/Lazy_loading
.. _Lars Corneliussen: http://startbigthinksmall.wordpress.com/2009/05/28/beautyofcode-jquery-plugin-for-syntax-highlighter-2-0-by-alex-gorbatchev/