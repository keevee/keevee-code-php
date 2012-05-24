mecode-php
==========

PHP lib for communicating with mecode app

to add this to your project:
----------------------------

    git submodule add git@github.com:momolog/mecode-php.git mecode


usage
-----
top of file (or auto include):

    <?php include("mecode/mecode.php"); ?>

in header:

    <? meheader(); ?>

to show codes:

    <? mecode() ?>

to show product list:

    <? meproducts([100, 101, 102]) ?>

where <code>[100, 101, 102]</code> is a list of numerical product id's
defined in the mecode application.
