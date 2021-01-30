<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\Comment;
use Meetee\App\Entities\Rate;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\CommentTable;
use Meetee\Libs\Database\Tables\RateTable;
use Meetee\Libs\Database\Tables\Pivots\CommentUserRateTable;
use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\View\Utils\Notification;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentIdValidator;

class RatingController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function rate($id, $commentId): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfSendDataInvalid((int) $id, (int) $commentId);
			
			$this->rateCommentAndPrintJsonResponse((int) $id, (int) $commentId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function trimPostValues(): void
	{
		foreach ($_POST as $key => $value)
			if (is_string($value))
				$_POST[$key] = trim($value);
	}

	private function dieIfTokenInvalid(string $name): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::getTokenFromPostRequestIfValidByNameAndUser(
			$name, $user))
			die;
	}

	private function dieIfSendDataInvalid(int $ratingId, int $commentId): void
	{
		$validator = new CommentIdValidator();
		
		if ($_POST['rating_id'] != $ratingId || 
			!preg_match('/^[1-9][0-9]*$/', $ratingId) || 
			!$validator->run($commentId))
			die;
	}

	private function rateCommentAndPrintJsonResponse(
		int $id, 
		int $commentId
	): void
	{
		$comment = $this->getCommentIfAuthorizedOrDie($commentId);
		$rate = $this->getRateOrDie($id);

		$this->rateComment($comment, $rate);
		$this->printJsonResponse($comment, $rate);
	}

	private function getCommentIfAuthorizedOrDie(int $id): Comment
	{
		$table = new CommentTable();
		$comment = $table->find($id);

		if (!$comment)
			die;

		$userId = AuthFacade::getUserId();

		if ($comment->authorId != $userId)
			die;

		return $comment;
	}

	private function getRateOrDie(int $id): Rate
	{
		$table = new RateTable();
		$rate = $table->find($id);

		if (!$rate)
			die;

		return $rate;
	}

	private function rateComment(Comment $comment, Rate $rate): void
	{
		$user = AuthFacade::getUser();
		$table = new CommentUserRateTable();

		$table->rateCommentByUser($rate, $comment, $user);
	}

	private function printJsonResponse(Comment $comment, Rate $rate): void
	{
		$data = [
			'comment_id' => $comment->getId(),
			'rate_id' => $rate->getId(),
		];

		echo json_encode($data);
	}

	public function delete($id): void
	{
		try {
			$this->trimPostValues();
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfCommentIdInvalid($id);

			$this->deleteComment((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function deleteComment(int $id): void
	{
		$table = new CommentTable();
		$table->delete($id);
	}
}