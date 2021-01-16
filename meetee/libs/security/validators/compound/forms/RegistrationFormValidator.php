<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserLoginValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserEmailValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSurnameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserBirthValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserPasswordValidator;

class RegistrationFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'login' => new NewUserLoginValidator(),
			'email' => new NewUserEmailValidator(),
			'name' => new UserNameValidator(),
			'surname' => new UserSurnameValidator(),
			'birth' => new UserBirthValidator(),
			'password' => new UserPasswordValidator(),
		];

		parent::__construct($validators);
	}

	public function run(array $values): bool
	{
		$this->errors = [];

		foreach ($this->validators as $attr => $validator) {
			if (!$validator->run($values[$attr])) {
				$this->errors[$attr] = $validator->errorMsg;
			}
		}
		
		if (empty($this->errors['password']) && 
			$values['password'] !== $values['repeat_password'])
			$this->errors['password'] = 'Both passwords must be the same.';

		return empty($this->errors);
	}
}