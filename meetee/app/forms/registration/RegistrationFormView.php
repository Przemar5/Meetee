<?php

namespace Meetee\App\Forms\Registration;

use Meetee\App\Forms\FormView;

class RegistrationFormView extends FormView
{
	private array $templates = [
		'input' => '<input type="$type" name="$name">'
	];

	public function get(?array $args = []): string
	{

	}
}