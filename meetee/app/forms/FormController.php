<?php

namespace Meetee\App\Forms;

abstract class FormController
{
	abstract public function checkToken(): void;

	abstract public function checkUser(): void;
}