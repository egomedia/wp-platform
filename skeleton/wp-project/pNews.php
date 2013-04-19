<?php
class pNews extends oNews {
	
	/*
	get latest news
	*/
	function getLatest($limit=5) {
		
		$args = array();
		$args['post_type'] = 'post';
		$args['orderby'] = 'post_date';
		$args['order'] = 'DESC';
		$args['posts_per_page'] = $limit;
		
		$rtn = (object)get_posts($args);
		
		foreach ($rtn as &$r) {
			$r->date = strtotime($r->post_date);
			$r->url = get_permalink($r->ID);
		}
		
	}
		
		
}
?>
