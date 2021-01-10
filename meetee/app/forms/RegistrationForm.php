<?php

namespace Meetee\App\Forms;

// use Meetee\App\Forms\FormView;
use Meetee\App\Forms\FormTemplate;
use Meetee\Libs\Security\Validators\Compound\Forms\RegistrationFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class RegistrationForm extends FormTemplate
{
	public function __construct()
	{
		$requestKeys = [
			'POST' => ['login', 'email', 'name', 'surname', 
				'birth', 'password', 'repeat_password'],
		];

		parent::__construct(
			new RegistrationFormValidator(),
			new TokenValidator(),
			$requestKeys
		);
	}
}