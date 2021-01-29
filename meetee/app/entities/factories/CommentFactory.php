<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Comment;
use Meetee\Libs\Database\Tables\CommentTable;
use Meetee\Libs\Security\AuthFacade;

class CommentFactory
{
	public static function createAndSaveCommentFromRequest(): Comment
	{
		$comment = new Comment();
		$comment->content = trim($_POST['content']);
		$comment->authorId = AuthFacade::getUser()->getId();
		
		$table = new CommentTable();
		$table->save($comment);

		return $comment;
	}
}