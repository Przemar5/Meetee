<?php

namespace Meetee\Libs\Security\Validators\Compound\Utils;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class UnsignedIntValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIssetValidator();
		$validators[] = ValidatorFactory::createIntValidator();
		$validators[] = ValidatorFactory::createMinValidator(1);
		$validators[] = ValidatorFactory::createMaxValidator(999999999999999);

		parent::__construct($validators);
	}
}