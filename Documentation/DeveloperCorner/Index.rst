﻿.. ==================================================
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

FAQ
---

Howto install JS vendors
^^^^^^^^^^^^^^^^^^^^^^^^

1. Download amd install node.js from http://nodejs.org/download/
2. Install bower

   .. code-block:: bash

      npm install bower

3. Install vendors from CLI

   .. code-block:: bash

      node_modules/.bin/bower install

4. Remove unneeded files in `Resources/Public/Javascript/vendor`:
      - `qunit` folder
      - `xregexp` folder
      - `prism/gulpfile.js` folder
      - all `*.min.js` files in `prism/components/`
      - all untracked files in `prism/`

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


Run unit tests
^^^^^^^^^^^^^^

   .. code-block:: bash

      cd packages/beautyofcode

      composer install

      cd .Build

      bash ./run_tests.sh
