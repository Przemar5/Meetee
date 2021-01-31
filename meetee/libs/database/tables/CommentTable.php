<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\User;

class CommentTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('comments', Comment::class, true);
	}

	protected function fetchEntity($data): Comment
	{
		$table = new UserTable();
		$user = $table->find($data['user_id']);

		$comment = new Comment();
		$comment->setId($data['id']);
		$comment->content = $data['content'];
		$comment->authorId = $user->getId();
		$comment->setCreatedAt($data['created_at']);
		$comment->setUpdatedAt($data['updated_at']);
		$comment->deleted = $data['deleted'];

		return $comment;
	}

	protected function getEntityData(Entity $comment): array
	{
		$this->throwExceptionIfInvalidClass($comment, Comment::class);

		$data = [];
		$data['id'] = $comment->getId();
		$data['content'] = $comment->content;
		$data['user_id'] = $comment->authorId;
		$data['deleted'] = $comment->deleted;

		return $data;
	}

	public function findByAuthor(User $user): ?array
	{
		return $this->findManyBy(['user_id' => $user->getId()]);
	}

	public function findLastFromByAuthorIdAndParentId(
		int $last, 
		int $limit, 
		int $authorId,
		?int $parentId
	): ?array
	{
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		
		if (is_null($parentId)) {
			$this->queryBuilder->where(['user_id' => $authorId]);
			$this->queryBuilder->whereStrings([
				'id < :id', 
				'deleted IS NULL OR deleted = 0', 
				'parent_id IS NULL'
			]);
		}
		else {
			$this->queryBuilder->where([
				'user_id' => $authorId,
				'parent_id' => $parentId,
			]);
			$this->queryBuilder->whereStrings([
				'id < :id', 
				'deleted IS NULL OR deleted = 0'
			]);
		}

		$this->queryBuilder->limit($limit);
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->setAdditionalBindings(['id' => $last]);
		$this->queryBuilder->setAdditionalBindings(['id' => $last]);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings(),
		);
	}
}