<?php

namespace Meetee\App\Forms;

abstract class FormView
{
	abstract public function get(?array $args = []): string;
}