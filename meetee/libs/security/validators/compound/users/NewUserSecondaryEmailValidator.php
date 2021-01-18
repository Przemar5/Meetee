<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class NewUserEmailValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(
			8, 'Secondary email must be 8 characters minimum.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			60, 'Secondary email must be equal or shorter than 60 characters long.');
		$validators[] = ValidatorFactory::createEmailValidator(
			'Secondary email has incorrect format.');
		$validators[] = ValidatorFactory::createNotExistValidator(
			'users', 'email', 'Secondary email already is in use. Login to Your account!');

		parent::__construct($validators);
	}
}