<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserPasswordValidator;

class ResetPasswordFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'password' => new UserPasswordValidator(),
		];

		parent::__construct($validators);
	}

	public function run(array $values): bool
	{
		$this->errors = [];

		foreach ($this->validators as $attr => $validator) {
			if (!$validator->run($values[$attr])) {
				$this->errors[$attr] = $validator->errorMsg;
			}
		}
		
		if (empty($this->errors['password']) && 
			$values['password'] !== $values['repeat_password'])
			$this->errors['password'] = 'Both passwords must be the same.';

		return empty($this->errors);
	}
}