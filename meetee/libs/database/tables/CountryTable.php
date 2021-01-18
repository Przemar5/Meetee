<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Country;
use Meetee\Libs\Database\Tables\Pivots\UserCountryTable;

class CountryTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('countries', Country::class, false);
	}

	protected function fetchEntity($data): Country
	{
		$role = new Country();
		$role->setId($data['id']);
		$role->name = $data['name'];

		return $role;
	}

	protected function getEntityData(Entity $country): array
	{
		$this->entityIsOfClassOrThrowException($country, Country::class);

		$data = [];
		$data['id'] = $role->getId();
		$data['name'] = $role->name;

		return $data;
	}

	public function getAll(): array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);

		$result = $this->database->findMany($this->queryBuilder->getResult());

		return array_map(fn($r) => $this->fetchEntity($r), $result);
	}
}