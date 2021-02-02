<?php

namespace Meetee\App\Forms;

use Meetee\App\Forms\FormTemplate;
use Meetee\Libs\Security\Validators\Compound\Forms\CommentFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class CommentForm extends FormTemplate
{
	public function __construct()
	{
		$requestKeys = [
			'POST' => ['content'],
		];

		parent::__construct(
			new CommentFormValidator(),
			new TokenValidator(),
			$requestKeys
		);
	}
}