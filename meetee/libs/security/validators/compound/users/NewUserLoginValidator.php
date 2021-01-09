<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

abstract class NewUserLoginValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			3, 'Login must be 3 characters minimum.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			60, 'Login must be equal or shorter than 60 characters long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\d]+$/', 
			'Login may contain only alphanumeric characters, hyphens and @.');
		$validators[] = ValidatorFactory::createNotExistValidator(
			'users', 'login', 'Login already is in use. Please try another.');

		parent::__construct($validators);
	}
}