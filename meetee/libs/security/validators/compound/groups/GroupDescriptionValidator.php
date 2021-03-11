<?php

namespace Meetee\Libs\Security\Validators\Compound\Groups;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class GroupDescriptionValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Group description is required.');
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(
			20, 'Group description must be equal or longer than 20 characters.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			2048, 'Group description is too long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\d\s\- \|\#\$\@\!\^\&\*\(\)\+\=\{\}\[\]\;\:\"\,\<\.\>\/\?]+$/u', 
			'Group description contains inproper characters.');

		parent::__construct($validators);
	}
}