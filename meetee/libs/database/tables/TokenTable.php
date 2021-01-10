<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Token;

class TokenTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('tokens', false);
	}

	public function getValidWithoutId(Token $token): ?Token
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
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

		$data = $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		if ($data)
			return $this->fetchEntity($data);

		return null;
	}

	protected function fetchEntity($data): Token
	{
		$token = new Token();
		$token->setId($data['id']);
		$token->setName($data['name']);
		$token->setValue($data['value']);
		$token->setUserId($data['user_id']);
		$token->setExpiry($data['expires']);

		return $token;
	}

	protected function getEntityData(Entity $token): array
	{
		$this->entityIsOfClassOrThrowException($token, Token::class);

		$data = [];
		$data['name'] = $token->getName();
		$data['value'] = $token->getValue();
		$data['user_id'] = $token->getUserId() ?? 0;
		$data['expires'] = $token->getExpiryString();

		return $data;
	}

	public function delete(Token $token): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where(['id' => $token->getId()]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}