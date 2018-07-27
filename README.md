Portfolio
=========

This is David Osborn's old portfolio website.

The website is written in PHP, backward-compatible HTML5 and CSS3, and
Javascript.  There are some Python and Bash scripts in the `utils` directory.
The `utils/ftpmirror.py` script comes from the
[FTP Mirror](https://github.com/davidcosborn/ftpmirror/) project.

All of the source code for the website is included in this repository, as well
as some of the content.  The heavier content has been left out, as it would
otherwise bog down the repository.

The website is driven by a custom PHP framework.  Its purpose is to organize the
content and make it easier to work with.  The framework may seem convoluded
until you understand how it works.


Directory Structure
-------------------

There are a few patterns in the directory structure of the proejct, which are
important to understand when using the framework:

* The page hierarchy is defined in the normal way, where each page gets its
  own directory containing an `index.php` file.

* Content includes assets (such as images and videos), hooks (which are
  explained later), PHP code, scripts, stylesheets, and other related
  things.  It can exist at one of two levels:
	1. The framework level, which includes fundamental content used or
	   provided by the framework, and is available to all pages.
	2. The page level, which includes page-specific content.  It is only
	   meant to be used by the page that contains it.

  The level of the content is determined by its location in the directory
  structure.  Files under the `framework` directory are at the framework
  level.  Files under an `include` directory are at the level of the page
  defined by its parent directory (which contains an `index.php` file).
  For example, `me/include/assets/DavidOsbornResume.pdf` is a copy of my
  resume which is included on the
  [About Me](http://davidcosborn.com/portfolio/me/) page.

* Some content can be auto-loaded by framework.  This includes PHP code,
  scripts, and stylesheets.  For this to happen, the files must be put in
  an `auto` subdirectory.  For example, `include/scripts/auto/tags.js`
  which implements tag filtering on the main page, will be automatically
  loaded by the framework when that page is opened.

* Third-party code is always put in a `thirdparty` subdirectory.  For
  example, `framework/php/thirdparty/auto/lessphp` contains lessphp, a
  compiler for LESS, the dynamic stylesheet language, implemented in PHP.
  Since it is also in an `auto` subdirectory, it will be automatically
  loaded by the framework.


Templates
---------

The framework defines a set of templates in the `framework/templates` directory
that pages can use to avoid repetitive boilerplate code.  For example, here is
the code for the `index.php` of the root page:

```php
<?php
require 'framework/php/init.php';
load_template('header-body-footer');
?>
```

The second line is required on every page that uses the framework to initialize
it.  The third line calls a function to load a specified template into the page.
If you don't specify a template, a default (minimalist) one will be used.

Templates typically contain hooks, which allow a page to insert custom HTML/PHP
content at specific locations in the output.  You can open a template file in
the `framework/templates` directory to see all of the hooks that it provides.

The framework checks for hook handlers in a page's `include/hooks` directory.
If it finds one, the file's content is loaded directly.  The framework also
defines its own hook handlers in the `framework/hooks` directory.

The order in which hook handlers are loaded depends on their priority.  A hook's
priority is defined by an optional `-X` suffix after its filename, where `X` is
a number between 0 and 100, with 100 being loaded first and 0 being loaded last.
If both the framework and the page define hooks with an extreme priority value,
the framework will take precedence. If no priority is specified, it will default
to 50.

Please note that this behaviour is subject to change as the framework develops.
Check the `framework/php/auto/template.php` file for the most accurate
information on the ordering of hook handlers.


Sizing
------

Sizes should always be specified in em units, as this will allow the page to be
scaled by scripting.  Scaling the page can make it more usable on mobile
devices.

You must be careful when setting the font-size property, because it will affect
the em sizing of all descendants.  In most cases, the font-size property should
only be set in leaf-node elements.

Note that the current implementation of the site uses px instead of em.  This
may be changed later.
