<?php
//v0.1
class oMenu {
  
	protected $menus;
	
	function __construct() {
		add_theme_support('menus');
		add_action('after_setup_theme', array($this, 'registerNavMenus'));
	}
	
	/*
	register nav manus
	*/
	function registerNavMenus() {
		register_nav_menus($this->menus);
	}
	
	/*
	draw menu
	*/
	function draw($menu_key, $settings=array()) {
		
		$defaults['theme_location'] = $menu_key;
		$defaults['container'] = '';
		$defaults['menu_class'] = '';
		
		$settings = array_merge($defaults, $settings);
		
		return wp_nav_menu($settings);
		
	}
	
	/*
	add menu
	*/
	function addMenu($key, $name) {
		$this->menus[$key] = $name;	
	}
	
}
?>
