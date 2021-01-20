<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Post;
use Meetee\App\Entities\User;

class PostTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('posts', Post::class, true);
	}

	protected function fetchEntity($data): Post
	{
		$table = new UserTable();
		$user = $table->find($data['user_id']);

		$post = new Post();
		$post->setId($data['id']);
		$post->content = $data['content'];
		$post->author = $user;
		$post->setCreatedAt($data['created_at']);
		$post->setUpdatedAt($data['updated_at']);
		$post->deleted = $data['deleted'];

		return $post;
	}

	protected function getEntityData(Entity $post): array
	{
		$this->throwExceptionIfInvalidClass($post, Post::class);

		$data = [];
		$data['id'] = $post->getId();
		$data['content'] = $post->content;
		$data['user_id'] = $post->author->getId();
		$data['deleted'] = $post->deleted;

		return $data;
	}
}