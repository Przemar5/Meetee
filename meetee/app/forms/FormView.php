<?php

namespace Meetee\App\Forms;

abstract class FormView
{
	abstract public function render(?array $args = []): void;
}