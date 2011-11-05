<?php

require('Clerk.php');

$post = array(
	'fname' => 'Peter',
	'sname' => 'Horne',
	'email' => 'peter@example.com',
	'verify_email' => 'peter@example.com',
	'can_contact' => false,
	'terms' => true,
);

$form = new Clerk($post);
$form->add('fname')->not_equals('Simon')->required(false);
$form->add('sname')->equals('Horne');
$form->addEmail('email');
$form->addEmail('verify_email')->equals($form->email);
$form->addBool('can_contact')->required(false);
$form->addBool('terms');


echo '<pre>';
if ($errors = $form->getErrors()) {
	echo 'Form is not valid:';
	var_dump($errors);
} else {
	echo 'Form is valid.';
}
