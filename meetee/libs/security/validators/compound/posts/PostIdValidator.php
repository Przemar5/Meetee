<?php

namespace Meetee\Libs\Security\Validators\Compound\Posts;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class PostIdValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIssetValidator();
		$validators[] = ValidatorFactory::createIntValidator();
		$validators[] = ValidatorFactory::createMinValidator(0);
		$validators[] = ValidatorFactory::createMaxValidator(99999999999);
		$validators[] = ValidatorFactory::createExistsValidator('posts', 'id');

		parent::__construct($validators);
	}
}