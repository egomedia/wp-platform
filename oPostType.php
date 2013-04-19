<?php
//v0.1
class oPostType {
  
	protected $id;
	protected $name;
	protected $labels;
	protected $args;
	
	function __construct() {
		
		$this->args['labels'] = $this->labels;
		
		if (!post_type_exists($this->name)) {
			register_post_type($this->name, $this->args);
		}
		
	}
	
}
?>
