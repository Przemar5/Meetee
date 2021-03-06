<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class UserNameValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Name is required.');
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			2, 'Name must be 2 characters minimum.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			40, 'Name must be equal or shorter than 40 characters long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w^\_\d]+$/u', 
			'Name may contain only letters.');

		parent::__construct($validators);
	}
}