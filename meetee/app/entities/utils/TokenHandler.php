<?php

namespace Meetee\App\Entities\Utils;

use Meetee\App\Entities\Token;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\TokenTable;

class TokenHandler
{
	public static function validateAndRemove(?Token $token): bool
	{
		if (is_null($token))
			return false;

		

		$tokenTable = new TokenTable();
		$fromTable = $tokenTable->getValidWithoutId($token);
		
		if (!$fromTable)
			return false;

		$fromTable->delete();

		return true;
	}
}