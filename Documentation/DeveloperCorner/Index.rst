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

FAQ
---

Howto install JS vendors
^^^^^^^^^^^^^^^^^^^^^^^^

#. Download amd install node.js from http://nodejs.org/download/

#. Install bower

   .. code-block:: bash

      npm install bower

#. Install vendors from CLI

   .. code-block:: bash

      node_modules/.bin/bower install

#. Remove unneeded files in `Resources/Public/Javascript/vendor`:

   - `qunit` folder
   - `xregexp` folder
   - `prism/gulpfile.js` folder
   - all `*.min.js` files in `prism/components/`
   - all untracked files in `prism/`

Run unit tests
^^^^^^^^^^^^^^

.. code-block:: bash

   cd packages/beautyofcode
   composer install
   cd .Build
   bash ./run_tests.sh
