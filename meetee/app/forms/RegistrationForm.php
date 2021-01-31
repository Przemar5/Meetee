<?php

namespace Meetee\App\Forms;

use Meetee\App\Forms\FormTemplate;
use Meetee\Libs\Security\Validators\Compound\Forms\RegistrationFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class RegistrationForm extends FormTemplate
{
	public function __construct()
	{
		$requestKeys = [
			'POST' => ['login', 'email', 'name', 'second_name', 
				'surname', 'birth', 'country', 'city',
				'zip', 'password', 'repeat_password'],
		];

		parent::__construct(
			new RegistrationFormValidator(),
			new TokenValidator(),
			$requestKeys
		);
	}
}