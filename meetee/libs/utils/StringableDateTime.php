<?php

namespace Meetee\Libs\Utils;

class StringableDateTime extends \DateTime 
{
	public function __toString()
	{
		return $this->format('Y-m-d H:i:s');
	}
}