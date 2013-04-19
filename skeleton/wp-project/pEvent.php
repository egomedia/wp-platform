<?php
class pEvent extends oPostType {
	
	protected $name = 'event';
	
	protected $labels = array(
		'name' 			=> 'Events',
		'singular_name' => 'Event',
		'add_new' 		=> 'Add event',
		'add_new_item' 	=> 'Add event',
		'edit_item' 	=> 'Edit event',
		'new_item' 		=> 'New event',
		'view_item' 	=> 'View event',
		'search_items' 	=> 'Search events',
		'not_found' 	=> 'No events found',
		'all_items' 	=> 'List events',
      	'menu_name' 	=> 'Events',
     	'name_admin_bar' => 'Event');

	protected $args = array(
		'public' 		=> true,
		'capability_type' => 'post',
		'hierarchical' 	=> false,
		'supports' 		=> array('title', 'editor', 'author', 'thumbnail', 'comments'),
		'taxonomies' 	=> array(),
		'has_archive' 	=> true,
		'rewrite' 		=> array('slug' => 'events')); 
	
	function __construct($id=false) {
		parent::__construct();
		$this->id = $id;
	}
	
	/*
	get upcoming events
	*/
	function getUpcoming($limit=-1) {
		
		$meta_query = array();
		$meta_query[0]['key'] = 'start_date';
		$meta_query[0]['type'] = 'DATE';
		$meta_query[0]['compare'] = '>=';
		$meta_query[0]['value'] = date('Y/m/d');
		
		$args = array();
		$args['post_type'] = $this->name;
		$args['meta_query'] = $meta_query;
		$args['meta_key'] = 'start_date';
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';
		$args['posts_per_page'] = $limit;
		
		return get_posts($args);
			
	}
		
}
?>
