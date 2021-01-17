<?php

namespace Meetee\Libs\Security\Validators\Factories;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSurnameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserBirthValidator;

class CompoundValidatorFactory
{
	public static function createUserValidator(
		string $property
	): CompoundValidator
	{
		switch ($property) {
			case 'name': return new UserNameValidator();
			case 'surname': return new UserSurnameValidator();
			case 'birth': return new UserBirthValidator();
		}
	}
}