<?php

namespace Meetee\Libs\Security\Validators\Compound\Tokens;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class TokenUserIdValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIntValidator();
		$validators[] = ValidatorFactory::createMinValidator(0);
		$validators[] = ValidatorFactory::createMaxValidator(99999999999);

		parent::__construct($validators);
	}
}