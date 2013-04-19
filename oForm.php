<?php
//v0.2
class oForm {
  
	public $errors = array();
	public $error_fields = array();
	
	protected $uniquevar;
	protected $input;
	protected $label;
	
	function __construct($uniquevar='posted') {
		$this->uniquevar = $uniquevar;
	}
	
	/*
	check required fields
	*/
	function checkRequired() {
		
		if (!$this->getStatus()) {
			return;
		}
		
		foreach (func_get_args() as $field) {
			
			if (empty($_POST[$field])) {
				$this->errors['required'] = 'Some required fields have not been completed';
				$this->error_fields[] = $field;
			}
			
		}
		
	}
	
	/*
	add error
	*/
	function addError($msg, $field=false) {
		
		$this->errors[] = $msg;
		
		if ($field) {
			$this->error_fields[] = $field;
		}
		
	}
	
	/*
	add error from wp
	*/
	function addSystemError($WP_Error) {
		$this->addError(substr(current(current($WP_Error->errors)), 0, -1));
	}
	
	/*
	add success
	*/
	function addSuccess($str) {
		$_SESSION[$this->uniquevar.'_success'] = $str;
	}
	
	/*
	put alerts
	*/
	function putAlerts() {
		$this->putErrors();
		$this->putSuccess();
	}
	
	/*
	put success
	*/
	function putSuccess() {
		
		if (empty($_SESSION[$this->uniquevar.'_success'])) {
			return;
		}
		
		echo '<div class="form-feedback success">';
		echo '<ul>';
		echo '<li>'.$_SESSION[$this->uniquevar.'_success'].'</li>';
		echo '</ul>';
		echo '</div>';
		
		unset($_SESSION[$this->uniquevar.'_success']);
		
	}
	
	/*
	put errors
	*/
	function putErrors() {
		
		if (empty($this->errors)) {
			return;
		}
		
		echo '<div class="form-feedback error">';
		echo '<ul>';
		
		foreach ($this->errors as $msg) {
			echo '<li>'.$msg.'</li>';
		}
		
		echo '</ul>';
		echo '</div>';
		
	}
	
	/*
	put error class
	*/
	function putErrorClass() {
		
		if (in_array($this->input, $this->error_fields)) {
			return 'error';	
		}
		
	}
	
	/*
	put ID
	*/
	function putID() {
		return $this->uniquevar.'-'.$this->input;	
	}
	
	/*
	put label
	*/
	function putLabel() {
		return $this->label;	
	}
	
	/*
	put input name
	*/
	function putInput() {
		return $this->input;	
	}
	
	/*
	put value
	*/
	function putValue($default=false) {
		
		if ($this->getStatus() && !empty($_POST[$this->input])) {
			return $_POST[$this->input];
		} else if ($default) {
			return $default;
		} else {
			return false;
		}
		
	}
	
	/*
	put uniquevar
	*/
	function putUniquevar() {
		
		echo '<div style="display:none">';
		echo '<input type="hidden" name="'.$this->uniquevar.'" value="1">';
		echo '</div>';
		
	}
	
	/*
	get status
	*/
	function getStatus() {
		
		if (isset($_POST[$this->uniquevar])) {
			return true;	
		} else {
			return false;	
		}
		
	}
	
	/*
	get success
	*/
	function getSuccess() {
		
		if ($this->getStatus() && empty($this->errors)) {
			return true;	
		} else {
			return false;	
		}
		
	}
	
	/*
	set current input
	*/
	function setInput($str) {
		$this->input = $str;
	}
	
	/*
	set current label
	*/
	function setLabel($str) {
		$this->label = $str;
	}
	
}
?>
