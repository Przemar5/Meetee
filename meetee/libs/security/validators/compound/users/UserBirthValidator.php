<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

abstract class UserBirthValidator extends CompoundValidator
{
	public function __construct()
	{
		// $validators[] = ValidatorFactory::createStringValidator('');
		// $validators[] = ValidatorFactory::createMinLengthValidator(
		// 	2, 'Surname must be 2 characters minimum.');
		// $validators[] = ValidatorFactory::createMaxLengthValidator(
		// 	70, 'Surname must be equal or shorter than 70 characters long.');
		// $validators[] = ValidatorFactory::createPatternValidator(
		// 	'/^[\w^\_\-\d]+$/u', 
		// 	'Name may contain only alpha characters.');

		// parent::__construct($validators);
	}
}