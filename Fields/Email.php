<?php

namespace Fields;

class Email extends Field {
	
	public function __construct($name, $value) {
		parent::__construct($name, $value);
		$this->attributes['type'] = 'email';
	}
	
	protected function validate_type($setting) {
		return filter_var($this->value, FILTER_VALIDATE_EMAIL);
	}
}