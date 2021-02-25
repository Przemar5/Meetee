<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Users\UserIdValidator;

class UserController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function friend($id): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfUserIdInvalid($id);
			
			$this->toggleFriendForId((int) $id);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieIfTokenInvalid(string $name): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::getTokenFromPostRequestIfValidByNameAndUser(
			$name, $user))
			die;
	}

	private function dieIfUserIdInvalid($id): void
	{
		$validator = new UserIdValidator();
		
		if (!preg_match('/^[1-9][0-9]*$/', $id) || 
			!$validator->run((int) $id))
			die;
	}

	private function toggleFriendForId(int $id): void
	{
		$table = new UserTable();
		$current = AuthFacade::getUser();
		$user = $table->find($id);

		if ($user->hasFriend($current))
			$user->removeFriend($current);
		else
			$user->addFriend($current);
	}

	private function getCommentOrDie(int $id): Comment
	{
		$table = new CommentTable();
		$comment = $table->find($id);

		if (!$comment)
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

	private function commentHasThatRate(Comment $comment, Rate $rate): bool
	{
		$user = AuthFacade::getUser();
		$table = new CommentUserRateTable();

		$result = $table->findOneBy([
			'comment_id' => $comment->getId(),
			'user_id' => $user->getId(),
			'rate_id' => $rate->getId(),
		]);

		return !empty($result);
	}

	private function rateComment(Comment $comment, Rate $rate): void
	{
		$user = AuthFacade::getUser();
		$table = new CommentUserRateTable();

		$table->rateCommentByUser($rate, $comment, $user);
	}

	private function unrateComment(Comment $comment): void
	{
		$user = AuthFacade::getUser();
		$table = new CommentUserRateTable();

		$table->removeRateForCommentByUser($comment, $user);
	}

	private function printJsonResponse(Comment $comment, ?Rate $rate = null): void
	{
		$data = [
			'comment_id' => $comment->getId(),
			'rate_id' => ($rate) ? $rate->getId() : null,
		];

		echo json_encode($data);
	}

	public function getByComment($commentId)
	{
		try {
			$this->dieIfCommentIdInvalid($commentId);
			
			$this->getCommentRatesAndPrintJsonResponse((int) $commentId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function getCommentRatesAndPrintJsonResponse(int $commentId): void
	{
		$table = new CommentUserRateTable();
		$result = $table->findManyBy(['comment_id' => $commentId]);

		echo json_encode($result);
	}
}