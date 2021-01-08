<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\Table;
use Meetee\App\Entities\User;

class UserTable extends Table
{
	public function __construct()
	{
		parent::__construct('user');
	}

	public function save(User $user): string
	{
		return (is_null($user->getId()))
			? $this->insert($user)
			: $this->update($user);
	}

	public function insert(User $user): string
	{
		$data = [];
		$data['username'] = $user->getUsername();
		$data['password'] = $user->getPassword();
		if (!is_null($user->isDeleted()))
			$data['deleted'] = $user->isDeleted();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function update(User $user): string
	{
		$values = [];
		$values['username'] = $user->getUsername();
		$values['password'] = $user->getPassword();
		if (!is_null($user->isDeleted()))
			$values['deleted'] = $user->isDeleted();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update($values);
		$this->queryBuilder->where(['id' => $user->getId()]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function find(int $id)
	{

	}
}