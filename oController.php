<?php
//v0.1
class oController {
  
	protected $archives;
	protected $page404;
	
	function __construct() {
		add_action('template_redirect', array($this, 'templateRedirect'));
		add_action('template_include', array($this, 'templateInclude'));
	}
	
	/*
	template redirect
	*/
	function templateRedirect() {
		
		if (is_404()) {
			query_posts('page_id='.$this->page404);
			$GLOBALS['post'] = $GLOBALS['wp_query']->post;
		}
		
	}
	
	/*
	template include
	*/
	function templateInclude($template) {
	
		global $wp_query;
		
		//archives
		if (isset($wp_query->post->ID) && isset($this->archives[$wp_query->post->ID])) {
		
			$args = array(
				'post_type' => $this->archives[$wp_query->post->ID]);
			
			query_posts($args);
			
			$posts = $wp_query->posts;
			
			if ($archive_template = get_archive_template()) {
				$template = $archive_template;	
			}
			
			wp_reset_query();
			
			$wp_query->posts = $posts;
			
		}
		
		//print_r($wp_query); exit;
		return $template;
		
	}
	
	/*
	set archive page
	*/
	function setArchivePage($post_id, $post_type) {
		$this->archives[$post_id] = $post_type;
		$GLOBALS['Admin']->makePageCore($post_id);
	}
	
	/*
	set 404 page
	*/
	function set404($post_id) {
		$this->page404 = $post_id;
		$GLOBALS['Admin']->makePageCore($post_id);
	}
	
}
?>
