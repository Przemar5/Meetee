<?php

namespace Meetee\Libs\Security\Validators\Compound\Comments;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class CommentOnProfileValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator();
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createExactLengthValidator(1);
		$validators[] = ValidatorFactory::createPatternValidator('/^(1|0)$/u');

		parent::__construct($validators);
	}
}