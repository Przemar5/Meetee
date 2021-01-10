<?php

namespace Meetee\Libs\Security\Validators\Compound;

abstract class FormValidator
{
	protected array $validators;
	protected array $errors = [];

	public function __construct(array $validators = [])
	{
		$this->validators = $validators;
	}

	public function run(array $values): bool
	{
		$this->errors = [];

		foreach ($this->validators as $attr => $validator) {
			if (!$validator->run($values[$attr])) {
				$this->errors[$attr] = $validator->errorMsg;
			}
		}

		return empty($this->errors);
	}

	public function getErrors(): array
	{
		return $this->errors;
	}
}