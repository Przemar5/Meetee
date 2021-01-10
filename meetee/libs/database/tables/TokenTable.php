<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Token;

class TokenTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('users', false);
	}

	protected function exists(Token $token): bool
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->table);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where([
			'name' => $token->getName(),
			'value' => $token->getValue(),
			'user_id' => $token->getUserId(),
		]);
		$this->queryBuilder->whereStrings(['expires >= NOW()']);
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->limit(1);

		$token = $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		return !empty($token);
	}

	protected function fetchEntity($data): Token
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
		$this->entityIsOfClassOrThrowException($token, Token::class);

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