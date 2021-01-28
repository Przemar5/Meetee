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
		parent::__construct('posts', Comment::class, true);
	}

	protected function fetchEntity($data): Comment
	{
		$table = new UserTable();
		$user = $table->find($data['user_id']);

		$post = new Comment();
		$post->setId($data['id']);
		$post->content = $data['content'];
		$post->authorId = $user->getId();
		$post->setCreatedAt($data['created_at']);
		$post->setUpdatedAt($data['updated_at']);
		$post->deleted = $data['deleted'];

		return $post;
	}

	protected function getEntityData(Entity $post): array
	{
		$this->throwExceptionIfInvalidClass($post, Comment::class);

		$data = [];
		$data['id'] = $post->getId();
		$data['content'] = $post->content;
		$data['user_id'] = $post->authorId;
		$data['deleted'] = $post->deleted;

		return $data;
	}

	public function findByAuthor(User $user): ?array
	{
		return $this->findManyBy(['user_id' => $user->getId()]);
	}

	public function findLastFromByAuthorId(int $last, int $limit, int $authorId): ?array
	{
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where(['user_id' => $authorId]);
		$this->queryBuilder->whereStrings(['id < :id', 'deleted IS NULL OR deleted = 0']);
		$this->queryBuilder->limit($limit);
		$this->queryBuilder->orderBy(['id']);
		$this->queryBuilder->orderDesc();
		$this->queryBuilder->setAdditionalBindings(['id' => $last]);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings(),
		);
	}
}