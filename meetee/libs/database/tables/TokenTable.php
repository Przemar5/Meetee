<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Token;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

class TokenTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('users', false);
	}

	private function fetchEntity($data): Token
	{
		$token = new Token();
		$token->setId($data['id']);
		$token->setName($data['name']);
		$token->setValue($data['value']);
		$token->setUserId($data['user_id']);
		$token->setExpires($data['expires']);

		return $token;
	}

	protected function getEntityData(Entity $token): array
	{
		$data = [];
		$data['name'] = $token->getName();
		$data['value'] = $token->getValue();
		$data['user_id'] = $user->getId() ?? 0;

		return $data;
	}

	public function delete(Token $token): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->table);
		$this->queryBuilder->delete();
		$this->queryBuilder->where(['id' => $token->getId()]);

		$this->sendQuery();
	}
}