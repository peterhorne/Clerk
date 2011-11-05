<?php

namespace Fields;

class Bool extends Field {
	
	protected $attributes = array(
		'required' => true,
	);
	
	public function __construct($value) {
		$this->value = (bool)$value;
	}	
}
