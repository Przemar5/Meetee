<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentBodyValidator;

class CommentFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'content' => new CommentBodyValidator(),
		];

		parent::__construct($validators);
	}
}