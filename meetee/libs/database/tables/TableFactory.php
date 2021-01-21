<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\RoleTable;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Database\Tables\CountryTable;

class TableFactory
{
	public static function createTable(string $name): TableTemplate
	{
		switch ($name) {
			case 'users': return new UserTable();
			case 'roles': return new RoleTable();
			case 'tokens': return new TokenTable();
			case 'countries': return new CountryTable();
			case 'posts': return new TokenTable();
		}
	}
}