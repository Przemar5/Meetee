<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Rate;

class CommentUserRateTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('comment_user_rate');
	}

	public function rateCommentByUser(
		Rate $rate, 
		Comment $comment,
		User $user
	): void
	{
		$this->removeRateForCommentByUser($comment, $user);
		$this->addRateForCommentByUser($rate, $comment, $user);
	}

	public function removeRateForCommentByUser(
		Comment $comment,
		User $user
	): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where([
			'comment_id' => $comment->getId(),
			'user_id' => $user->getId(),
		]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function addRateForCommentByUser(
		Rate $rate,
		Comment $comment,
		User $user
	): void
	{
		$data = [
			'rate_id' => $rate->getId(),
			'comment_id' => $comment->getId(),
			'user_id' => $user->getId(),
		];

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}