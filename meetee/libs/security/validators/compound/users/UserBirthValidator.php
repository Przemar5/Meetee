<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class UserBirthValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIsSetValidator();
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createExactLengthValidator(10);
		$validators[] = ValidatorFactory::createPatternValidator(
			'^[]$');
		// $validators[] = ValidatorFactory::createMaxLengthValidator(
		// 	70, 'Surname must be equal or shorter than 70 characters long.');
		// $validators[] = ValidatorFactory::createPatternValidator(
		// 	'/^[\w^\_\-\d]+$/u', 
		// 	'Name may contain only alpha characters.');

		// parent::__construct($validators);
		parent::__construct([]);
	}
}