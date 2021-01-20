<?php

namespace Meetee\App\Entities\Visitors;

use Meetee\Libs\Data_structures\Visitor\Visitor;
use Meetee\Libs\Data_structures\Visitor\Station;

class CommentVisitor implements Visitor
{
	public function visit(Station $station): void;
}