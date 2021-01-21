<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Token;

class TokenTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('tokens', Token::class, false);
	}

	public function getValidByToken(Token $token): ?Token
	{
		$data = [
			'name' => $token->name,
			'value' => $token->value,
		];
		
		if (isset($token->userId))
			$data['user_id'] = $token->userId;

		return $this->getValidBy($data);
	}

	public function getValidBy(array $conditions): ?Token
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where($conditions);
		$this->queryBuilder->whereStrings(['expiry >= NOW()']);
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
		$token->name = $data['name'];
		$token->value = $data['value'];
		$token->userId = $data['user_id'];
		$token->setExpiry($data['expiry']);

		return $token;
	}

	protected function getEntityData(Entity $token): array
	{
		$this->throwExceptionIfInvalidClass($token, Token::class);

		$data = [];
		$data['name'] = $token->name;
		$data['value'] = $token->value;
		$data['user_id'] = $token->userId ?? 0;
		$data['expiry'] = $token->getExpiryString();

		return $data;
	}

	public function popValidByToken(Token $token): ?Token
	{
		$token = $this->getValidByToken($token);

		if (!is_null($token))
			return null;

		$copy = $token;
		$this->delete($token->getId());

		return $copy;
	}

	public function popValidBy(array $conditions): ?Token
	{
		$token = $this->getValidBy($conditions);

		if (is_null($token))
			return null;
		
		$copy = $token;
		$this->delete($token->getId());
		
		return $copy;
	}

	public function deleteOld(): void
	{
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->whereStrings(['expiry < NOW()']);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}