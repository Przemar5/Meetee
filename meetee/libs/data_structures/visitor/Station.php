<?php

namespace Meetee\Libs\Data_structures\Visitor;

use Meetee\Libs\Data_structures\Visitor\Visitor;

interface Station
{
	public function accept(Visitor $visitor): void;
}