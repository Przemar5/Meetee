<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class ProfilePhotoValidator extends CompoundValidator
{
	public function __construct($value = null)
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Password is required.');
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(
			8, 'Password must be at least 8 characters long.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			60, 'Password must be equal or shorter than 60 characters.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^(?:(?=.*?\p{N})(?=.*?[\p{S}\p{P} ])(?=.*?\p{Lu})(?=.*?\p{Ll}))[^\p{C}]{8,60}$/u', 
			'Password must contain a lowercase and uppercase letter, '.
			'a number and special character.');

		parent::__construct($validators);
	}
}