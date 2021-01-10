<?php

namespace Meetee\Libs\Security\Validators\Compound\Tokens;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class TokenValueValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator();
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createExactLengthValidator(64);
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[A-Za-z0-9]+$/');

		parent::__construct($validators);
	}
}