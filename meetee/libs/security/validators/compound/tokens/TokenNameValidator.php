<?php

namespace Meetee\Libs\Security\Validators\Compound\Tokens;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class TokenNameValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator();
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(1);
		$validators[] = ValidatorFactory::createMaxLengthValidator(255);
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[A-Za-z0-9_\-]+$/');

		parent::__construct($validators);
	}
}