<?php

namespace Fields;

class Email extends Field {
	
	public function __construct($value) {
		parent::__construct($value);
		$this->attributes['type'] = 'email';
	}
	
	protected function validate_type($setting) {
		return filter_var($this->value, FILTER_VALIDATE_EMAIL);
	}
}