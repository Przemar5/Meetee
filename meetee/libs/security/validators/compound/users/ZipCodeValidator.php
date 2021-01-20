<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class ZipCodeValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			5, 'Zip code has inproper format.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			6, 'Zip code has inproper format.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^\d{2}\-?\d{3}$/u', 'Zip code has inproper format.');

		parent::__construct($validators);
	}
}