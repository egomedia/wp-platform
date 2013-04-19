<?php
//v0.1
class oGlobal {
  	
	/*
	check if page is in a post type
	*/
	function isPostType($type) {
		
		global $post;
		global $wp_query;
		
		if (isset($post) && $type == $post->post_type) {
			return true;
		} else if ($type == $wp_query->query_vars['post_type']) {
			return true;
		} else {
			return false;
		}
		
	}
		
}
?>
