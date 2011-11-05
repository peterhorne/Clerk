<?php

class Clerk {
	
	private $fields = array();
	private $values;
	
	public function __construct($values) {
		spl_autoload_register(array($this, 'autoload'));
		$this->values = $values; // $values is usually $_POST
	}
	
	/**
	 * Validate all fields and return errors, or false if none
	 */
	public function getErrors() {
		$errors = array();
		foreach ($this->fields as $field_name => $field) {
			if ($field_errors = $field->getErrors()) {
				$errors[$field_name] = $field_errors;
			}
		}
		return empty($errors) ? false : $errors;
	}
	
	/**
	 * Returns a new instance of a Field class
	 */
	public function addField($type, $name) {
		$args = func_get_args();
		unset($args[0]); // Remove $type from $args
		$args[1] = isset($this->values[$name]) ? $this->values[$name] : null; // Replace $name with the field's value
		
		$reflection = new ReflectionClass("Fields\\$type");
		return $this->fields[$name] = $reflection->newInstanceArgs($args);
	}
	
	/**
	 * Provide an alias to addField.
	 * 
	 * Without alias:
	 *     $form->addField('Bool', 'Terms');
	 *
	 * With alias:
	 *     $form->addBool('terms');
	 */
	public function __call($name, $args) {
		if (substr($name, 0, 3) != 'add') { // We only want to target methods such as: add, addEmail, addBool
			trigger_error('Call to undefined method '.__CLASS__."::$name()", E_USER_ERROR);
		}
		
		$type = substr($name, 3) ?: 'Field';
		array_unshift($args, $type); // Prepend $args with $type
		return call_user_func_array(array($this, 'addField'), $args);
	}
	
	public function __get($name) {
		if (!array_key_exists($name, $this->fields)) {
			trigger_error('Undefined property '.__CLASS__."::$name", E_USER_NOTICE);
			return null;
		}
		
		return $this->fields[$name];
	}
	
	private function autoload($name) {
		if (strpos($name, 'Fields') === 0) {
			$path = str_replace('\\', '/', $name).'.php';
			if (file_exists(__DIR__."/$path")) {
				require($path);
				return true;
			}
		}
	}
}
