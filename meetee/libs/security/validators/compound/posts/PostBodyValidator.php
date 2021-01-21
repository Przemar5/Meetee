<?php

namespace Meetee\Libs\Security\Validators\Compound\Posts;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class PostBodyValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Post body is required.');
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(
			20, 'Post body must be longer or equal 20 characters.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			65535, 'Post body is too long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\d\s\- \|\#\$\@\!\^\&\*\(\)\+\=\{\}\[\]\;\:\"\,\<\.\>\/\?]+$/u', 
			'Post body contains inproper characters.');

		parent::__construct($validators);
	}
}