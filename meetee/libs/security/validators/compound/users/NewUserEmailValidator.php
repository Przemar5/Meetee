<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class NewUserEmailValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Email is required.');
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			8, 'Email must be 8 characters minimum.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			60, 'Email must be equal or shorter than 60 characters long.');
		$validators[] = ValidatorFactory::createEmailValidator(
			'Email has incorrect format.');
		$validators[] = ValidatorFactory::createNotExistValidator(
			'users', 'email', 'Email already is in use. Login to Your account!');

		parent::__construct($validators);
	}
}