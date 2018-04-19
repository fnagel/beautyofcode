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
- Add the static template in your main typoscript (go to: list module, edit your TS template, tab: includes,
  add one of the static templates named “beautyOfCode”).
- Depending on your configuration you must add the jQuery core file yourself.


.. _admin-configuration:

Configuration
-------------

Enable syntax highlighting (install EXT: t3editor) in BE by using
enable_t3editor option.


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

_Please note: this issue is no longer relevant in version 3.x_

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
https://github.com/fnagel/beautyofcode/issues


Problems with line numbering and line highlighting in Prism
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

There was a bug in Prism which prevented proper line numbering and line highlighting.
This issue should be fixed in beautyofcode v 3.0.0 as we updated PrimsJS.
For more information please head over to the `corresponding github issue entry <PrismLineNumberingIssue_>`_.
To ensure proper functionality, you **must** provide the `markup` brush after
the `php` Prism component/brush (like in the default configuration).

.. _PrismLineNumberingIssue: https://github.com/LeaVerou/prism/issues/149


SyntaxHighlighter 4.x
^^^^^^^^^^^^^^^^^^^^^

SyntaxHighlighter is available as 4.x now, but it seems the new version is no longer dynamic
when it comes to brushes, themes, etc and needs a built process using Gulp.
