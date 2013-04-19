<?php
//v0.2
class oAdmin {
  
	protected $core_posts = array();
	
	function __construct() {
		
		$this->addDashboardPostTypes();
		$this->makePageCore(get_option('page_on_front'));
		
		add_action('wp_trash_post', array($this, 'restrictDelete'));
		add_action('delete_post', array($this, 'restrictDelete'));
		add_action('admin_head', array($this, 'hideDeleteControl'));
		add_action('admin_footer', array($this, 'disableTrashControl'));
		
	}
	
	/*
	make pages core
	*/
	function makePageCore($post_id) {
		$this->core_posts[] = $post_id;
	}
	
	function makePagesCore() {
		foreach (func_get_args() as $post_id) {
			$this->core_posts[] = $post_id;
		}
	}
	
	function restrictDelete($post_id) {
		
		if (in_array($post_id, $this->core_posts)) {
			echo 'This is a core page and cannot be deleted.';
			exit;
		}
		
	}
	
	function hideDeleteControl() {
		
		$rtn = '';
		$rtn .= '<style type="text/css">';
		
		foreach ($this->core_posts as $post_id) {
			
			//$rtn .= '#posts-filter #post-'.$post_id.' .check-column input { display:none !important; }';
			$rtn .= '#posts-filter #post-'.$post_id.' .row-actions .trash { display:none !important; }';
			$rtn .= '#posts-filter #post-'.$post_id.' .row-actions .inline { display:none !important; }';
			
			if (isset($_GET['post']) && $_GET['post'] == $post_id) {
				$rtn .= '#submitdiv #delete-action { display:none !important; }';
				$rtn .= '#submitdiv .edit-post-status { display:none !important; }';
				$rtn .= '#submitdiv #post-status-select { display:none !important; }';
				$rtn .= '#submitdiv .edit-visibility { display:none !important; }';
				$rtn .= '#submitdiv #post-visibility-select { display:none !important; }';
				$rtn .= '#edit-slug-buttons { display:none !important; }';
				$rtn .= '#editable-post-name { background:none !important; }';
				$rtn .= '#editable-post-name input { display:none !important; }';
			}
			
		}
		
		$rtn .= '</style>';
			
		echo $rtn;
		
	}
	
	function disableTrashControl() {
		
		$rtn = '';
		$rtn .= '<script type="text/javascript">';
		
		foreach ($this->core_posts as $post_id) {
			$rtn .= 'jQuery("#posts-filter #post-'.$post_id.' .check-column input").attr("disabled", "disabled").hide();';
		}
		
		$rtn .= '</script>';
			
		echo $rtn;
		
	}
	
	/*
	remove tags
	*/
	function removeTags() {
		add_action('admin_init', array($this, 'remove_tags_metabox'));
		add_action('admin_menu', array($this, 'remove_tags_submenu'));
		add_action('admin_head', array($this, 'remove_tags_right_now'));
	}
	
	function remove_tags_metabox() {
		remove_meta_box('tagsdiv-post_tag', 'post', 'normal');	
	}
	
	function remove_tags_submenu() {
		global $submenu;
		unset($submenu['edit.php'][16]);
	}
	
	function remove_tags_right_now() {
		echo '
			<style type="text/css">
			#dashboard_right_now .b-tags,
			#dashboard_right_now .tags { display:none; }
			</style>';
	}
	
	/*
	remove categories
	*/
	function removeCategories() {
		add_action('admin_init', array($this, 'remove_categories_metabox'));
		add_action('admin_menu', array($this, 'remove_categories_submenu'));
		add_action('admin_head', array($this, 'remove_categories_right_now'));
	}
	
	function remove_categories_metabox() {
		remove_meta_box('categorydiv', 'post', 'normal');	
	}
	
	function remove_categories_submenu() {
		global $submenu;
		unset($submenu['edit.php'][15]);
	}
	
	function remove_categories_right_now() {
		echo '
			<style type="text/css">
			#dashboard_right_now .b-cats,
			#dashboard_right_now .cats { display:none; }
			</style>';
	}
	
	/*
	rename posts object
	*/
	function renamePosts($label_single='News', $label_plural='News') {
		
		$GLOBALS['label_single'] = $label_single;
		$GLOBALS['label_plural'] = $label_plural;
		
		add_action('init', array($this, 'change_post_object_labels'));
		add_action('admin_menu', array($this, 'change_post_menu_label'));
		add_action('wp_before_admin_bar_render', array($this, 'change_post_admin_bar_label'));
		add_action('admin_head', array($this, 'remove_posts_right_now'));
		add_action('right_now_content_table_end', array($this, 'add_posts_right_now'));
		
	}
	
	function change_post_object_labels() {
		
		global $wp_post_types;
		global $label_single;
		global $label_plural;
		
		$labels = &$wp_post_types['post']->labels;
		
		$labels->name = $label_plural;
		$labels->singular_name = $label_single;
		$labels->add_new = 'Add '.strtolower($label_single);
		$labels->add_new_item = 'Add '.strtolower($label_single);
		$labels->edit_item = 'Edit '.strtolower($label_single);
		$labels->new_item = 'New '.strtolower($label_single);
		$labels->view_item = 'View '.strtolower($label_single);
		$labels->search_items = 'Search '.strtolower($label_plural);
		$labels->not_found = 'No '.strtolower($label_plural).' found';
		$labels->not_found_in_trash = 'No '.strtolower($label_plural).' found in trash';
		
	}
	
	function change_post_menu_label() {
		
		global $menu;
		global $submenu;
		global $label_single;
		global $label_plural;
		
		$menu[5][0] = $label_plural;
		
		$submenu['edit.php'][5][0] = 'View '.strtolower($label_plural);
		$submenu['edit.php'][10][0] = 'Add '.strtolower($label_single);
		
	}
	
	function change_post_admin_bar_label() {
		
		global $wp_admin_bar;
		global $label_single;
		global $label_plural;
		
		$wp_admin_bar->add_node(array('id' => 'new-post', 'title' => $label_single));
		
	}
	
	function remove_posts_right_now() {
		
		echo '
			<style type="text/css">
			#dashboard_right_now .b-posts,
			#dashboard_right_now .posts { display:none; }
			</style>';
		
	}
	
	function add_posts_right_now() {
		
		global $label_single;
		global $label_plural;
		
		$count 	= wp_count_posts('post');
		$num 	= number_format($count->publish);
		$text 	= _n($label_single, $label_plural, intval($count->publish));
		
		if (current_user_can('edit_posts')) {
			$num = '<a href="edit.php">'.$num.'</a>';
			$text = '<a href="edit.php">'.$text.'</a>';
		}
	
		echo '<tr>';
		echo '<td class="first b b-'.strtolower($label_plural).'">'.$num.'</td>';
		echo '<td class="t '.strtolower($label_plural).'">'.$text.'</td>';
		echo '</tr>';
		
	}
	
	/*
	cleanup dashboard
	*/
	function cleanupDashboard() {
		$this->removeDashboardWidgets();
		$this->addDashboardPostTypes();
	}
	
	/*
	add all post types to dashboard
	*/
	function addDashboardPostTypes() {
		add_action('right_now_content_table_end', array($this, 'add_dashboard_post_types'));	
	}
	
	function add_dashboard_post_types() {
		
		$args 		= array('public' => true, 'show_in_menu' => true, '_builtin' => false);
		$output 	= 'object';
		$operator 	= 'and';
		$post_types = get_post_types($args, $output, $operator);
		
		foreach ($post_types as $r) {
			
			$count 	= wp_count_posts($r->name);
			$num 	= number_format($count->publish);
			$text 	= _n($r->labels->singular_name, $r->labels->name, intval($count->publish));
			
			if (current_user_can('edit_posts')) {
				$num = '<a href="edit.php?post_type='.$r->name.'">'.$num.'</a>';
				$text = '<a href="edit.php?post_type='.$r->name.'">'.$text.'</a>';
			}
		
			echo '<tr>';
			echo '<td class="first b b-'.$r->name.'">'.$num.'</td>';
			echo '<td class="t '.$r->name.'">'.$text.'</td>';
			echo '</tr>';
		
		}
		
	}
	
	
	/*
	remove dashboard widgets
	*/
	function removeDashboardWidgets($keep_widgets=array()) {
		
		$GLOBALS['keep_widgets'] = $keep_widgets;
		
		add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));
		add_action('load-index.php', array($this, 'hide_welcome_screen'));
		
	}
	
	function remove_dashboard_widgets() {
		
		global $wp_meta_boxes;
		global $keep_widgets;
		
		if (!in_array('dashboard_recent_comments', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		}
			
		if (!in_array('dashboard_incoming_links', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		}
		
		if (!in_array('dashboard_plugins', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		}
			
		if (!in_array('dashboard_quick_press', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		}
			
		if (!in_array('dashboard_recent_drafts', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		}
			
		if (!in_array('dashboard_primary', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		}
			
		if (!in_array('dashboard_secondary', $keep_widgets)) {
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		}
		
	}
	
	function hide_welcome_screen() {
		
		$user_id = get_current_user_id();
		
		if (get_user_meta($user_id, 'show_welcome_panel', true)) {
			update_user_meta($user_id, 'show_welcome_panel', 0);
		}
		
	}
	
}
?>
