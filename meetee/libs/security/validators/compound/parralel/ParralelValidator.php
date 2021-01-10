<?php

namespace Meetee\Libs\Security\Validators\Compound;

abstract class ParralelValidator
{
	protected array $validators;
	protected array $errors = [];

	public function __construct(
		array $validators = [], 
		array $afterAllValidators = []
	)
	{
		$this->validators = $validators;
	}

	public function run(array $values): bool
	{
		$this->errors = [];

		foreach ($this->validators as $validator) {
			
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