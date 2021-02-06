<?php

namespace Meetee\Libs\Security\Validators\Compound\Groups;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class GroupIdValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createIssetValidator();
		$validators[] = ValidatorFactory::createIntValidator();
		$validators[] = ValidatorFactory::createMinValidator(1);
		$validators[] = ValidatorFactory::createMaxValidator(99999999999);
		$validators[] = ValidatorFactory::createExistsValidator('groups', 'id');

		parent::__construct($validators);
	}
}