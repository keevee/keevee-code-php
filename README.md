mecode-php
==========

PHP lib for communicating with mecode app

in header:

    <? meheader(); ?>

to show codes:

    <? mecode() ?>

to show product list:

    <? meproducts([100, 101, 102]) ?>

where [100, 101, 102] is a list of numerical product id's
defined in the mecode application.
