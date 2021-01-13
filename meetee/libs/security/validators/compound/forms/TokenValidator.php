<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Tokens\TokenNameValidator;
use Meetee\Libs\Security\Validators\Compound\Tokens\TokenValueValidator;
use Meetee\Libs\Security\Validators\Compound\Tokens\TokenUserIdValidator;

class TokenValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'name' => new TokenNameValidator(),
			'value' => new TokenValueValidator(),
			'user_id' => new TokenUserIdValidator(),
		];

		parent::__construct($validators);
	}
}