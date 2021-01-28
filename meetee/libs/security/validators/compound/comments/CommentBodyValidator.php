<?php

namespace Meetee\Libs\Security\Validators\Compound\Comments;

use Meetee\Libs\Security\Validators\Compound\CompoundValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;

class CommentBodyValidator extends CompoundValidator
{
	public function __construct()
	{
		$validators[] = ValidatorFactory::createNotEmptyValidator(
			'Comment body is required.');
		$validators[] = ValidatorFactory::createStringValidator();
		$validators[] = ValidatorFactory::createMinLengthValidator(
			1, 'Comment body is required.');
		$validators[] = ValidatorFactory::createMaxLengthValidator(
			4096, 'Comment body is too long.');
		$validators[] = ValidatorFactory::createPatternValidator(
			'/^[\w\d\s\- \|\#\$\@\!\^\&\*\(\)\+\=\{\}\[\]\;\:\"\,\<\.\>\/\?]+$/u', 
			'Comment body contains inproper characters.');

		parent::__construct($validators);
	}
}