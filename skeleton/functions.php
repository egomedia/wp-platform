<?php
/*
load platform
*/
require(ABSPATH.'wp-platform/init.php');

/*
constants
*/

/*
add theme support
*/
add_theme_support('post-thumbnails');

/*
post types
*/

/*
admin - global object
*/
$Admin->removeCategories();
$Admin->removeTags();
$Admin->makePageCore(19);
$Admin->makePageCore(50);

/*
controller - global object
*/
$Controller->set404(4);
$Controller->setArchivePage(47, 'post');

/*
menus
*/
$Menu = new oMenu();
$Menu->addMenu('main_menu', 'Main menu');

/*
branding
*/
new pBranding();

/*
register scripts and styles
*/

?>
