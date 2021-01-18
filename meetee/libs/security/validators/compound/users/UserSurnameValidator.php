<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class UserSurnameValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Surname is required.');
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			2, 'Surname must be 2 characters minimum.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			70, 'Surname must be equal or shorter than 70 characters.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\-^\_\d]+$/u', 
			'Surname may contain only letters and dashes.');

		parent::__construct($validators);
	}
}