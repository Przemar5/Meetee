<?php

namespace Meetee\App\Controllers\Rest;

use Meetee\App\Controllers\Rest\RestController;
use Meetee\Libs\View\ViewTemplate;
use Meetee\Libs\Database\Tables\CountryTable;

class CountryController extends RestController
{
	public function getAll(): void
	{
		$table = new CountryTable();
		$countries = $table->getAllRaw();

		echo json_encode($countries);
	}
}