<?php

namespace Meetee\Libs\Security\Validators\Compound\Comments;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class CommentIdValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIssetValidator();
		$validators[] = ValidatorFactory::createIntValidator();
		$validators[] = ValidatorFactory::createMinValidator(0);
		$validators[] = ValidatorFactory::createMaxValidator(99999999999);
		$validators[] = ValidatorFactory::createExistsValidator('comments', 'id');

		parent::__construct($validators);
	}
}