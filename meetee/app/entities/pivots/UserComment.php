<?php

namespace Meetee\App\Entities\Pivots;

use Meetee\App\Entities\Pivots\Pivot;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Comment;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\CommentTable;

class UserComment extends Pivot
{
	public function commentsForUser(User $user): ?array
	{
		$table = new PostTable();
		
		return $table->findByAuthor($user);
	}

	public function commentAuthor(Comment $post): User
	{
		$table = new UserTable();

		return $table->find($comment->authorId);
	}
}