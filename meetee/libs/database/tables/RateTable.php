<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Rate;
// use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

class RateTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('rates', Rate::class, false);
	}

	protected function fetchEntity($data): Rate
	{
		$rate = new Rate();
		$rate->setId($data['id']);
		$rate->setName($data['name']);

		return $rate;
	}

	protected function getEntityData(Entity $rate): array
	{
		$this->entityIsOfClassOrThrowException($rate, Rate::class);

		$data = [];
		$data['id'] = $rate->getId();
		$data['name'] = $rate->getName();

		return $data;
	}
}