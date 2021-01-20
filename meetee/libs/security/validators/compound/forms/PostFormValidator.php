<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Posts\PostBodyValidator;

class PostFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'content' => new PostBodyValidator(),
		];

		parent::__construct($validators);
	}
}