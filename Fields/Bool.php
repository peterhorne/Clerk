<?php

namespace Fields;

class Bool extends Field {
	
	protected $attributes = array(
		'required' => true,
	);
	
	public function __construct($name, $value) {
		$this->value = (bool)$value;
	}	
}
