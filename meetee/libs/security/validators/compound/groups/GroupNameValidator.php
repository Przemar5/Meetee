<?php

namespace Meetee\Libs\Security\Validators\Compound\Groups;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class GroupNameValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createStringValidator('');
		$validators[] = ValidatorFactory::createMinLengthValidator(
			3, 'Group name must be longer or equal 3 characters.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			255, 'Group name must be equal or shorter than 255 characters long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\d\s\- \|\#\$\@\!\^\&\*\(\)\+\=\{\}\[\]\;\:\"\,\<\.\>\/\?]+$/u', 
			'Group name contains inproper characters.');

		parent::__construct($validators);
	}
}