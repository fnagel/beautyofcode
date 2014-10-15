.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _admin-manual:

Administrator Manual
====================

.. _admin-installation:

Installation
------------

- Install the extension via extension manager.
- Add the static template in your main typoscript (go to: list module, root
  page, edit your root TS template, tab: includes, add one of the static
  templates named “beautyOfCode”).
- You must add the jQuery core file yourself or by installing ext:t3jquery
  and make usage of the shipped backend module to compile your necessary jQuery
  library. Since version 2.0.0 it's possible to use syntax highlighting
  without using jQuery.

**Please note**: It's strongly recommended to add one line in your
localconf.php to force CDATA escaping within the flexforms in TYPO3. Please see
know FAQ section.

.. _admin-configuration:

Configuration
-------------

Enable syntax highlighting (install EXT: t3editor) in BE by using
enable_t3editor option.

**Please note**: Some extension manager settings have been removed in version
0.7.0. Use TypoScript instead.

.. _admin-upgrade-guide:

Upgrade Guide
-------------

If there's no upgrade guide, there' s no need to change anything. This guide
does not covering extending via PHP (aka XCLASS or Hooks) – you need to check
changes for yourself.

0.5 to 0.6
^^^^^^^^^^

This version introduces HTML templating. That's why some TS options are not
longer available or renamed:

- plugin.tx_beautyofcode_pi1.label.wrap is removed as it's now configured via template
- plugin.tx_beautyofcode_pi1.label.show is renamed to showLabel
- plugin.tx_beautyofcode_pi1.wrap is removed as it's now configured via template

0.6 to 0.7
^^^^^^^^^^

Big update: finally its possible to use this extension without jQuery. Massive
remanufacturing and code changes. Please consider this release as a beta
version as I did test every feature the best I can but not sure if everything
will work out on every TYPO3 installation.

What you need to know:

- You'll need to change your TypoScript code

   - paths for base, scripts and styles
   - addjQuery and selector have been moved to the jquery. Array
   - some variables have been slightly renamed

- Extension Manager settings has been removed, use TS configuration instead
- SyntaxHighlighter v3 changed some features (learn more: “Which version to choose”)

Please see the changelog for all changes in detail.

1.0 to 2.0
^^^^^^^^^^

Rewritten for Extbase/Fluid.

What you need to know:

- Execute update script within extension manager to update adjusted plugin names
  in database
- You'll need to change your TypoScript code

  - SyntaxHighlighter v2 + beautyofcode (jQuery) abstraction layer was removed
  - include the correct static template
  - removed TypoScript constants / setup paths:

    - jquery.scriptUrl (adapt template if you need to adjust)
    - jQueryNoConflict (inline JS is wrapped in module pattern style code)
    - config.strings (<f:translate /> vhs in templates)
    - addjQuery (please make sure to include jQuery by yourself if you want to
      use the `includeAsDomReady` / `onReadyCallback` configuration settings
    - selector

Please see the changelog for all changes in detail.

2.0 to 2.1
^^^^^^^^^^

Introducing Dynamic page assets as feature.

Why?

If you don't want to deploy your TYPO3.CMS project in order to activate new brushes,
it is no possible to use dynamic brush discovery & brush registration. You just have
to add the static TS template "beautyOfCode (Dynamic page assets)".

This will also help developers to easily add and test new JavaScript syntaxhighlighting
libraries with a minimum configuration effort (see `BrushDiscovery` in ext_localconf.php).

It will even allow *you* to add new syntax highlighting libraries without the need to
modify ext:beautyofcode but use all it's technical infrastructure (Backend editing /
highlighting, the page layout view hook etc.).

What does it do?

The static template unregisters the `plugin.tx_beautyofcode.settings.brushes` TypoScript
configuration and adds a special content object definition to your PAGE cObj.

Please have a look at the _AdministrationManual, section "page.1407710024 - What is this?"
in order to understand what will happen under the hood.

Technical information / Performance discussion

Please note: this feature *may* lower the performance of the frontend rendering of
your TYPO3.CMS instance. While no benchmarking / profiling was done, this is due the
fact that the additonal PAGE cObject added will bootstrap and run the Extbase framework
and the Fluid template engine just for adding JavaScript and CSS assets to the page.

Changed AssetPathViewHelper

If you are using customized templates for rendering, you have to adjust a view helper reference
if your are using SyntaxHighlighter. This change was necessary to introduce a better separation
of namespaces for view helpers needed for specific highlighter libraries.

Before: boc:standaloneAssetPath()
After: boc:highlighter.syntaxHighlighter.assetPath()

.. _admin-faq:

FAQ
---

Which version to choose?
^^^^^^^^^^^^^^^^^^^^^^^^

This extension ships with two syntax highlighting libraries.

Choose either SyntaxHighlighter which supports lazyloading of the necessary CSS
and JS files  after the DOM ready event has fired. It runs standalone using an
autoloading feature.

Prism is also a standalone library without any dependencies. There are some
benefits and disadvantages by using each version:

SyntaxHighlighter
"""""""""""""""""

- runs without using a JavaScript framework
- uses latest version of SyntaxHighlighter
- slim and less feature rich (but with almost the same functionality)

Prism
"""""

- runs without using a JavaScript framework
- based on regular expressions for syntax parsing
- syntax files has dependencies with each other (php → clike)
- lightweight

Input is encoded and saved without “<”,  “>”, etc.
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This is a server side problem within PHP 5 respective in the libxml module
(1.6.32 and higher) with an existing work around.

**Solution:**

Add this line in your localconf.php in typo3conf directory.

   .. code-block:: php

      $TYPO3_CONF_VARS['BE']['flexformForceCDATA']  = '1';

This fix is non breaking and works only for new added elements. Older flexform
values should / could be fixed manually. This is a recommended default setting
for every TYPO3 installation (see mailing list why).

**Bug**:

http://bugs.typo3.org/view.php?id=9359

**Mailingslist Post**:

`[TYPO3-dev] A ticking ticking timebomb with libxml update to 2.7.1? <libxmlbug_>`_

I don't like my users to overwrite my default TS configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Take a look at the extension sp_betterflex: “Exclude static flexform fields
made by extensions like normal table fields in backend group configuration or
via TSConfig.”

http://typo3.org/extensions/repository/view/sp_betterflex/current/

Install the extension and add these lines to your TSconfig:

   .. code-block:: ts

      TCEFORM.tt_content.beautyofcode_cGutter.disabled = 1
      TCEFORM.tt_content.beautyofcode_cCollapse.disabled = 1

.. _libxmlbug: http://lists.netfielders.de/pipermail/typo3-dev/2009-August/036436.html

“Missing Ext. Manager configuration” error in FE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You forgot to save the extension manager configuration. Please go to Extension
Manager → beautyOfCode → click Update

“t3lib_div::array_merge_recursive_overrule()” error in BE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This error could look like this:

`PHP Catchable Fatal Error: Argument 1 passed to t3lib_div::array_merge_recursive_overrule()
must be an array, null given, called in /html/typo3/typo3_src-4.4.2/typo3/sysext/rtehtmlarea/class.tx_rtehtmlareaapi.php
on line 80 and defined in /html/typo3/typo3_src-4.4.2/t3lib/class.t3lib_div.php line 2059`

This issue was already patched but seems to be introduced again in specific
TYPO3 version. Some kind of regression I guess. Sadly Im not able to reproduce
the issue and it even seems to be an Core / RTE problem, so please take a look
at these bugtracker issues which should help you to solve the problem:

- http://bugs.typo3.org/view.php?id=15864
- http://bugs.typo3.org/view.php?id=15893

Enable t3editor in BE
^^^^^^^^^^^^^^^^^^^^^

You need to enable the *enable_t3editor* option in Extension Manager and check
your user settings.

"TypeError: lang is undefined" when using PRISM
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This JavaScript console error is caused by wrong order or missing language
component files. Some Prism languages depend on each other and need to be added
in correct order. Please check your TypoScript. Take a look in the Prism
component JS files to check language dependencies.

No highlighting in FE
^^^^^^^^^^^^^^^^^^^^^

If you're using the SyntaxHighlighter library, and decided to make usage of the
includeAsDomReady setting set to "jquery", you must ensure to either install the
extension t3jquery and compile a suitable jQuery libary on your own or include a
jQuery core file by yourself. You need to add the jQuery Core file manually.

I get a Java-Script error in FE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

See above.

Do you use other JavaScript frameworks? Please see down below.

I'm using MooTools, ExtJS or similar
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

No problem, the generated inline javascript code uses the module pattern and
therefore doesn't need the noConflict option to be set.

Please note and keep in mind if jQuery must be loaded (minified about 56KB)
this will lead to more traffic and less performance for your users.

How to change language strings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Adjust the templates according to your needs. Please follow the Fluid
templating guides for how to use your own templates for a specific extension.

Also have a look at the following article:

http://xavier.perseguers.ch/tutoriels/typo3/articles/managing-localization-files.html

Where to post improvements or bugs
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Please feel free add questions, bugs and improvements at
http://forge.typo3.org/projects/extension-beautyofcode/issues