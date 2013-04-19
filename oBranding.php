<?php
//v0.2
class oBranding {
  
	protected $name;
	protected $url;
	protected $login_logo;
	protected $admin_logo;
	
	function __construct() {
		
		if (strpos($this->login_logo, 'http://') === false) {
			$this->login_logo = get_template_directory_uri().'/'.$this->login_logo;
		}
		
		if (strpos($this->admin_logo, 'http://') === false) {
			$this->admin_logo = get_template_directory_uri().'/'.$this->admin_logo;
		}
		
		add_filter('admin_footer_text', array($this, 'footer'));
		add_action('login_head', array($this, 'loginLogo'));
		add_action('admin_bar_menu', array($this, 'adminLogo'), 0);
		
	}
	
	/*
	footer branding
	*/
	function footer() {
		
		echo 'Created by <a href="'.$this->url.'">'.$this->name.'</a>. ';
		echo 'Powered by <a href="http://wordpress.org">WordPress</a>.';
		
	}
	
	/*
	login branding
	*/
	function loginLogo() { 
		
		echo '<style type="text/css">h1 a { background:url('.$this->login_logo.') no-repeat !important; }</style>';
		
	}
	
	/*
	admin bar branding
	*/
	function adminLogo($wp_admin_bar) {
	
		$wp_admin_bar->add_node(array(
			'id' 	=> 'branding-logo', 
			'title' => '<img src="'.$this->admin_logo.'" alt="'.$this->name.'" />', 
			'href' 	=> $this->url
		));
	
	}
	
}
?>
