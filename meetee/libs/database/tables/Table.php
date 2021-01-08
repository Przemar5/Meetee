<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;
use Meetee\Libs\Database\Factories\DatabaseAbstractFactory;
use Meetee\Libs\Database\DatabaseTemplate;

abstract class Table
{
	public QueryBuilderTemplate $queryBuilder;
	protected string $name;
	protected DatabaseTemplate $database;

	public function __construct(string $name)
	{
		$this->name = $name;
		$this->database = DatabaseAbstractFactory::createDatabase();
		$this->queryBuilder = DatabaseAbstractFactory::createQueryBuilder();
	}

	// abstract public function getData(Entity $entity): array;
}