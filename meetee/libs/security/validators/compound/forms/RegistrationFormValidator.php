<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserLoginValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserEmailValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSecondNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSurnameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\CountryIdValidator;
use Meetee\Libs\Security\Validators\Compound\Users\CityNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\ZipCodeValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserBirthValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserPasswordValidator;
use Meetee\Libs\Security\Validators\Compound\Users\GenderValidator;

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

		$optional = [
			'second_name' => new UserSecondNameValidator(),
			'country' => new CountryIdValidator(),
			'city' => new CityNameValidator(),
			'zip' => new ZipCodeValidator(),
			'gender' => new GenderValidator(),
		];

		parent::__construct($validators, $optional);
	}

	public function run(array $values): bool
	{
		$this->errors = [];
		
		if (!empty($values['country']))
			$values['country'] = (int) $values['country'];

		foreach ($this->validators as $attr => $validator) {
			if (!$validator->run($values[$attr])) {
				$this->errors[$attr] = $validator->errorMsg;
			}
		}

		foreach ($this->optional as $attr => $validator) {
			if (!empty($values[$attr]) && !$validator->run($values[$attr])) {
				$this->errors[$attr] = $validator->errorMsg;
			}
		}
		
		if (empty($this->errors['password']) && 
			$values['password'] !== $values['repeat_password'])
			$this->errors['password'] = 'Both passwords must be the same.';

		return empty($this->errors);
	}
}