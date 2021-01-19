<?php

namespace Meetee\Rest;

use Meetee\Libs\Database\Tables\CountryTable;

class CountriesRest
{
	public function selectAll(): void
	{
		$table = new CountryTable();
		$countries = $table->getAll();
		
		echo json_encode($countries);
	}
}