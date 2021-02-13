<?php

namespace Meetee\Libs\Security\Validators\Compound\Users;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class GenderValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createMaxLengthValidator(255);
		$validators[] = ValidatorFactory::createPatternValidator('/^\w*$/u');

		parent::__construct($validators);
	}
}