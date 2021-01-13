<?php

namespace Meetee\App\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Security\AuthFacade;

abstract class FormTemplate
{
	protected array $requestKeys = [];
	protected array $errors = [];
	protected bool $isValid = true;
	protected FormValidator $validator;
	protected TokenValidator $tokenValidator;

	public function __construct(
		FormValidator $validator, 
		TokenValidator $tokenValidator,
		?array $requestKeys = []
	)
	{
		$this->validator = $validator;
		$this->tokenValidator = $tokenValidator;
		$this->requestKeys = $requestKeys;
	}

	public function validate(): bool
	{
		$data = $this->getRequestData();
		$this->isValid = $this->validator->run($data);

		return $this->isValid;
	}

	protected function getRequestData(): array
	{
		$data = [];

		foreach ($this->requestKeys as $method => $names) {
			$method = $this->getGlobalByMethod($method);

			foreach ($names as $name) {
				$data[$name] = $method[$name] ?? null;
			}
		}
		return $data;
	}

	protected function getGlobalByMethod(string $method): array
	{
		switch ($method) {
			case 'GET': 	return $_GET;
			case 'POST': 	return $_POST;
			default: 		return $_GET;
		}
	}

	public function isValid(): bool
	{
		return $this->isValid;
	}

	public function getErrors(): array
	{
		return $this->validator->getErrors();
	}
}