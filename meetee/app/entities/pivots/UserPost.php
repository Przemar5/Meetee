<?php

namespace Meetee\App\Entities\Pivots;

use Meetee\App\Entities\Pivots\Pivot;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Post;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\PostTable;

class UserPost extends Pivot
{
	public function postsForUser(User $user): ?array
	{
		$table = new PostTable();
		
		return $table->findByAuthor($user);
	}

	public function postAuthor(Post $post): User
	{
		$table = new UserTable();

		return $table->find($post->authorId);
	}
}