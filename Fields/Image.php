<?php

namespace Fields;

class Image extends Field {
	
	public function __construct($name, $value) {
		$value = isset($_FILES[$name]) && !$_FILES[$name]['error'] ? $_FILES[$name] : null;
		parent::__construct($name, $value);
		$this->attributes['type'] = 'image';
	}
	
	protected function validate_type($setting) {
		return $this->value && (bool)getimagesize($this->value['tmp_name']);
	}
}
