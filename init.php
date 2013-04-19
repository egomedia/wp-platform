<?php
//0.1

/*
sessions
*/
if (!session_id()) {
  session_start();
}

/*
autoload classes
*/
function __autoload($name) {

	if ($name{0} == 'o') {
		$file = ABSPATH.'wp-platform/'.$name.'.php';
	} else if ($name{0} == 'p') {
		$file = get_template_directory().'/wp-project/'.$name.'.php';
	}
	
	if (isset($file) && file_exists($file)) {
		require($file);
	}

}

/*
global
*/
$Global = new oGlobal();

/*
admin - must be initiated before oController
*/
$Admin = new oAdmin();

/*
controller
*/
$Controller = new oController();

/*
template
*/
$Template = new oTemplate();

/*
filter
*/
$Filter = new oFilter();
?>
