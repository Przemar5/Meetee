<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Groups\GroupNameValidator;
use Meetee\Libs\Security\Validators\Compound\Groups\GroupDescriptionValidator;

class GroupCreateFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'name' => new GroupNameValidator(),
			'description' => new GroupDescriptionValidator(),
		];

		parent::__construct($validators);
	}
}