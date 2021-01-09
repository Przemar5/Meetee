<?php

namespace Meetee\App\Forms;

abstract class FormModel
{
	protected array $data = [];

	abstract public function insertData(array $data): void;

	abstract public function getData(): array;

	abstract public function validate(): void;

	abstract public function sanitize(): void;
}