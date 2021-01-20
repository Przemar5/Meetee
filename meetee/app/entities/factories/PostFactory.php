<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Post;
use Meetee\Libs\Database\Tables\PostTable;
use Meetee\Libs\Security\AuthFacade;

class PostFactory
{
	public static function createAndSavePostFromRequest(): Post
	{
		$post = new Post();
		$post->content = trim($_POST['content']);
		$post->author = AuthFacade::getUser();
		
		$table = new PostTable();
		$table->save($post);

		return $post;
	}
}