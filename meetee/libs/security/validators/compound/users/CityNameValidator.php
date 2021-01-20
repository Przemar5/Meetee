<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class CityNameValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			2, 'City name must be longer or equal 2 characters.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			180, 'City name must be equal or shorter than 180 characters long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\- \(\)]+$/u', 
			'City name may contain only letters, dashes, spaces and parentheses.');

		parent::__construct($validators);
	}
}