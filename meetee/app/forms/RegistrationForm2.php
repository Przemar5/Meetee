<?php

namespace Meetee\App\Forms;

use Meetee\App\Forms\Form;
use Meetee\Libs\Security\Validators\Compound\Forms\RegistrationFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class RegistrationForm2 extends Form
{
	public function __construct()
	{
		parent::__construct(
			new RegistrationFormValidator(),
			new TokenValidator()
		);

		$this->formFields = ['login', 'email', 'name', 'surname', 
			'birth', 'password', 'repeat_password'];
		$this->formMethod = 'POST';
		$this->tokenName = 'csrf_registration_token';
		$this->tokenMethod = 'POST';
	}
}