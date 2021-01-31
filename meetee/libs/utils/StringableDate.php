<?php

namespace Meetee\Libs\Utils;

class StringableDate extends \DateTime 
{
	public function __toString()
	{
		return $this->format('Y-m-d');
	}
}