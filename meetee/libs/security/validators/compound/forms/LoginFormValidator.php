<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserEmailValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserPasswordValidator;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Security\Hash;

class LoginFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'email' => new UserEmailValidator(),
		];

		parent::__construct($validators);
	}

	public function run(array $values): bool
	{
		$this->errors = [];

		if (!$this->validators['email']->run($values['email']))
			$this->errors['general'] = 
				'Invalid credentials. Please try again.';

		return empty($this->errors);
	}
}