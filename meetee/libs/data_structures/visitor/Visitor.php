<?php

namespace Meetee\Libs\Data_structures\Visitor;

use Meetee\Libs\Data_structures\Visitor\Station;

interface Visitor
{
	public function visit(Station $station): void;
}