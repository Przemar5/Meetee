<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserLoginValidator;
use Meetee\Libs\Security\Validators\Compound\Users\NewUserEmailValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSurnameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserBirthValidator;

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
		];

		parent::__construct($validators);
	}
}