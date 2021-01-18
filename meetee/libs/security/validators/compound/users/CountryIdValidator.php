<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class CountryIdValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIntValidator('1');
		$validators[] = ValidatorFactory::createMinValidator(0, '2');
		$validators[] = ValidatorFactory::createMaxValidator(99999999999, '3');
		$validators[] = ValidatorFactory::createExistsValidator(
			'countries', 'id', '4');

		parent::__construct($validators);
	}
}