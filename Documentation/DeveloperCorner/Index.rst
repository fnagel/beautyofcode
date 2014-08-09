.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _developer:

Developer Corner
================

.. _generated-markup:

Generated markup
----------------

Syntax Highligther
^^^^^^^^^^^^^^^^^^

   .. code-block:: html
      :linenos:

      <pre class="php">
         CODE GOES HERE
      </pre>

Prism
^^^^^

   .. code-block:: html
      :linenos:

      <pre class="language-php">
         <code>
            CODE GOES HERE
         </code>
      </pre>

Adjusting the Fluid template output
-----------------------------------



Plugin rendering
^^^^^^^^^^^^^^^^

Create a TYPO3.CMS extension which should encapsulate the template adjustments
of the installed ext:beautyofcode extension in your TYPO3.CMS instance.

After that, create the necessary directories and copy the template files to the
created folders:

   .. code-block:: bash

      ~$ cd typo3conf/ext/
      ~$ mkdir -p [YOUR_EXTENSION]/Resources/Private/{Layouts,Templates}
      ~$ cp -R beautyofcode/Resources/Private/Layouts/* [YOUR_EXTENSION]/Resources/Private/Layouts/
      ~$ cp -R beautyofcode/Resources/Private/Templates/Content/ [YOUR_EXTENSION]/Resources/Private/Templates/

BrushLoader rendering
^^^^^^^^^^^^^^^^^^^^^

If you want adjust the brushloader rendering, create a TYPO3.CMS extension and
the necessary directory structure. Copy the template files with these commands:

   .. code-block:: bash

      ~$ cd typo3conf/ext/
      ~$ mkdir -p [YOUR_EXTENSION]/Resources/Private/BrushLoader
      ~$ cp -R beautyofcode/Resources/Private/BrushLoader/* [YOUR_EXTENSION]/Resources/Private/BrushLoader/

After that, you must implement a SignalSlot dispatcher connection in order to
overwrite the BrushLoaderView layouts, partials and template files during
initializing:

   .. code-block:: php

      $pageRendererHook = 'EXT:[YOUR_EXTENSION]/Classes/View/Beautyofcode/BrushLoaderView.php';
      $pageRendererHook .= ':[YOUR_VENDOR]\\[YOUR_EXTENSIOn]\\View\\Beautyofcode\\BrushLoaderView->overridePaths';
      $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = $pageRendererHook;
      \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
          'TYPO3\\Beautyofcode\\View\\BrushLoaderView',
          'overridePaths',
          '[YOUR_VENDOR]\\[YOUR_EXTENSION]\\View\\Beautyofcode\\BrushLoaderView',
          'overridePaths'
      );

   .. code-block:: php

      namespace [YOUR_VENDOR]\[YOUR_EXTENSION]]\View\Beautyofcode;
      class BrushLoaderView {
          public function overridePaths(\TYPO3\Beautyofcode\View\BrushLoaderView $brushLoaderView) {
            $brushLoaderView->setLayoutRootPath(...);
            $brushLoaderView->setPartialsRootPath(...);
            $brushLoaderView->setTemplatePathAndFilename(...);
          }
      }

FAQ
---

Howto install prism
^^^^^^^^^^^^^^^^^^^

1. Download node.js binaries from http://nodejs.org/download/
2. extract tar.gz
3. edit ~/.bashrc and extend $PATH

   .. code-block:: bash

      export PATH=${PATH}:/path/to/extracted/targz/content/bin

4. install bower

   .. code-block:: bash

      /path/to/extracted/targz/content/bin/npm install bower

5. create .bowerrc to install package within Resources/Public/Javascript/vendor

   .. code-block:: js

      {
         "directory": "./Resources/Public/Javascript/vendor/"
      }

6. install prism from CLI

   .. code-block:: bash

      ./node_modules/.bin/bower install prism#gh-pages

Howto add BOM to all reStructuredText files
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While editing the files in Eclipse, the BOM possibly gets removed, this script
will help you to re-add the UTF8 BOM again.

Simply put that into the extension root directory, add executable rights (`chmod u+x ...`).
If you are still experiencing issues, just comment out the last line and execute the script
multiple times until no output is displayed. Then uncomment the last line.

   .. code-block:: bash

      #!/bin/bash
      for F in $(ls -R Documentation/*.rst Documentation/*/*.rst Documentation/*.txt)
      do
        if [[ -f $F && `head -c 3 $F` == $'\xef\xbb\xbf' ]]; then
            # file exists and has UTF-8 BOM
            mv $F $F.bak
            tail -c +4 $F.bak > $F
            rm -f $F.bak
            echo "removed BOM from $F"
        fi
      done
      for file in $(ls -R Documentation/*.rst Documentation/*/*.rst Documentation/*.txt); do sed -i '1s/^/\xef\xbb\xbf/' "$file"; done