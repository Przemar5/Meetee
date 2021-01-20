<?php

namespace Meetee\App\Forms;

use Meetee\App\Forms\FormTemplate;
use Meetee\Libs\Security\Validators\Compound\Forms\PostFormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;

class PostForm extends FormTemplate
{
	public function __construct()
	{
		$requestKeys = [
			'POST' => ['content'],
		];

		parent::__construct(
			new PostFormValidator(),
			new TokenValidator(),
			$requestKeys
		);
	}
}