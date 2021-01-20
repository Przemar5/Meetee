<?php

namespace Meetee\App\Forms;

use Meetee\App\Forms\FormTemplate;
use Meetee\Libs\Security\Validators\Compound\Forms\LoginFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class LoginForm extends FormTemplate
{
	public function __construct()
	{
		$requestKeys = [
			'POST' => ['email', 'password'],
		];

		parent::__construct(
			new LoginFormValidator(),
			new TokenValidator(),
			$requestKeys
		);
	}
}