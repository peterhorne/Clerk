<?php

namespace Fields;

class Field {
	
	protected $value;
	protected $attributes = array(
		'required' => true,
		'type' => null,
		'max_size' => null,
		'min_size' => null,
		'equals' => null,
		'not_equals' => null,
	);
	
	public function __construct($name, $value) {
		$this->value = $value;
	}
	
	protected function validate_required($setting) {
		return (bool)$this->value;
	}
	
	protected function validate_max_size($setting) {
		return $this->value <= $setting;
	}
	
	protected function validate_min_size($setting) {
		return $this->value >= $setting;
	}
	
	protected function validate_equals($setting) {
		return $this->value == $setting;
	}
	
	protected function validate_not_equals($setting) {
		return $this->value != $setting;
	}
	
	public function getErrors() {
		$errors = array();
		
		// Field passes validation if it is not required and false/null
		if (!$this->attributes['required'] && !$this->value) {
			return $errors;
		}
		
		foreach ($this->attributes as $attribute => $setting) {
			if (!is_null($setting)) {
				if (!method_exists($this, 'validate_'.$attribute)) { // call_user_func doesn't trigger an error if the method does not exist
					trigger_error(__CLASS__." has not implemented a validator method for: $attribute", E_USER_ERROR);
				}
				
				if(!call_user_func(array($this, 'validate_'.$attribute), $setting)) {
					$errors[] = $attribute;
				}
			}
		}
		
		return $errors;
	}
	
	public function __call($name, $args) {
		if (!array_key_exists($name, $this->attributes)) {
			trigger_error('Call to undefined method '.__CLASS__."::$name()", E_USER_ERROR);
		}
		
		$this->attributes[$name] = $args[0];
		return $this;
	}
	
	public function __toString() {
		return $this->value;
	}
}
