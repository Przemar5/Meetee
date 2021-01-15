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
			'/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|(1|2)[0-9]|(3[0-1]))$/u', 
			'Invalid format. Please try again.');
		$validators[] = ValidatorFactory::createDateAfterValidator(
			'-100 years', 'You are too old.');
		$validators[] = ValidatorFactory::createDateBeforeValidator(
			'-5 years', 'You are too young.');
		
		parent::__construct($validators);
	}
}