<?php

namespace Meetee\App\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Forms\TokenValidator;
use Meetee\Libs\Security\AuthFacade;

abstract class Form
{
	protected array $formFields = [];
	protected string $formMethod = 'POST';
	protected string $tokenName;
	protected string $tokenMethod = 'POST';
	protected array $errors = [];
	protected bool $isValid = true;
	protected FormValidator $formValidator;
	protected TokenValidator $tokenValidator;

	public function __construct(
		FormValidator $formValidator, 
		TokenValidator $tokenValidator
	)
	{
		$this->formValidator = $formValidator;
		$this->tokenValidator = $tokenValidator;
	}

	public function trimFormValues(): void
	{
		$this->trimValuesByNamesAndMethd($this->formFields, $this->formMethod);
	}

	public function trimValuesByNamesAndMethod(array $names, string $method): void
	{
		if ($this->formMethod === 'GET') {
			$this->trimValuesInGet($names);
		}
		elseif ($this->formMethod === 'POST') {
			$this->trimValuesInPost($names);
		}
	}

	public function trimValuesInGet(array $names): void
	{
		foreach ($this->formFields as $field) {
			if (isset($_GET[$field]) && is_string($_GET[$field]))
				$_GET[$field] = trim($_GET[$field]);
		}
	}

	public function trimValuesInPost(array $names): void
	{
		foreach ($this->formFields as $field) {
			if (isset($_POST[$field]) && is_string($_POST[$field]))
				$_POST[$field] = trim($_POST[$field]);
		}
	}

	public function validate(): bool
	{
		$this->isValid = $this->validateToken() && $this->validateData();
		
		return $this->isValid;
	}

	protected function validateToken(): bool
	{
		$method = $this->getGlobalByHttpMethod($this->tokenMethod);
		$data = [
			'name' => $this->tokenName,
			'value' => $method[$this->tokenName],
		];
		
		return $this->tokenValidator->run($data);
	}

	protected function validateData(): bool
	{
		$method = $this->getGlobalByHttpMethod($this->formMethod);
		$fields = $this->formFields;
		$values = array_map(fn($f) => $method[$f], $this->formFields);
		$data = array_combine($fields, $values);

		return $this->formValidator->run($data);
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

	protected function getGlobalByHttpMethod(string $method): array
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