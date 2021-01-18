<?php

namespace Meetee\Libs\Security\Validators\Factories;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSecondNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserSurnameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserBirthValidator;
use Meetee\Libs\Security\Validators\Compound\Users\CityNameValidator;
use Meetee\Libs\Security\Validators\Compound\Users\ZipCodeValidator;

class CompoundValidatorFactory
{
	public static function createUserValidator(
		string $property
	): CompoundValidator
	{
		switch ($property) {
			case 'name': return new UserNameValidator();
			case 'secondName': return new UserSecondNameValidator();
			case 'surname': return new UserSurnameValidator();
			case 'birth': return new UserBirthValidator();
			case 'city': return new CityNameValidator();
			case 'zipCode': return new ZipCodeValidator();
		}
	}
}