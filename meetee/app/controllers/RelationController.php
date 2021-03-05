<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Controllers\ErrorController;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Utils\TokenFacade;
use Meetee\App\Entities\Factories\TokenFactory;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\Pivots\UserUserRelationTable;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\Security\Validators\Compound\Users\UserIdValidator;
use Meetee\Libs\Security\Validators\Compound\Relations\RelationIdValidator;

class RelationController extends ControllerTemplate
{
	private static string $tokenName = 'csrf_token';
	private array $errors = [];

	public function request($userId, $relationId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfUserIdInvalid($userId);
			$this->dieIfRelationIdInvalid($relationId);
			
			$this->requestRelationWithUserIdAndPrinMessage(
				(int) $relationId, (int) $userId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function dieIfTokenInvalid(string $name): void
	{
		$user = AuthFacade::getUser();

		if (!TokenFacade::getTokenFromPostRequestIfValidByNameAndUser(
			$name, $user))//popTokenIfValidByNameAndUser
			die;
	}

	private function dieIfUserIdInvalid($id): void
	{
		$validator = new UserIdValidator();
		
		if (!preg_match('/^[1-9][0-9]*$/', $id) || 
			!$validator->run((int) $id))
			die;
	}

	private function dieIfRelationIdInvalid($id): void
	{
		$validator = new RelationIdValidator();
		
		if (!preg_match('/^[1-9][0-9]*$/', $id) || 
			!$validator->run((int) $id))
			die;
	}

	private function requestRelationWithUserIdAndPrinMessage(
		int $relationId, 
		int $userId
	): void
	{
		$table = new UserTable();
		$logged = AuthFacade::getUser();
		$user = $table->find($userId);

		$this->requestRelation($logged, $user, $relationId);
		$this->printJsonResponseAndDie(
			"You have requested for friendship with " . $user->login . 
			". Wait until it'll be accepted.");
	}

	private function requestRelation(
		User $first, 
		User $second, 
		int $relationId
	): void
	{
		$table = new UserUserRelationTable();
		$table->requestForRelation($first, $second, $relationId);
	}

	private function breakRelation(
		User $first, 
		User $second, 
		int $relationId
	): void
	{
		$table = new UserUserRelationTable();
		$table->cancelRelation($first, $second, $relationId);
	}

	private function printJsonResponseAndDie(string $message): void
	{
		die(json_encode(['message' => $message]));
	}

	public function cancelRequest($userId, $relationId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfUserIdInvalid($userId);
			$this->dieIfRelationIdInvalid($relationId);
			
			$this->cancelRelationRequestWithUserIdAndPrinMessage(
				(int) $relationId, (int) $userId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function cancelRelationRequestWithUserIdAndPrinMessage(
		int $relationId, 
		int $userId
	): void
	{
		$table = new UserTable();
		$logged = AuthFacade::getUser();
		$user = $table->find($userId);

		$this->cancelRelationRequest($logged, $user, $relationId);
		$this->printJsonResponseAndDie('Request has been canceled.');
	}

	private function cancelRelationRequest(
		User $first,
		User $second,
		int $relationId
	): void
	{
		$table = new UserUserRelationTable();
		$table->cancelRequestedRelation($first, $second, $relationId);
	}

	public function discard($userId, $relationId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfUserIdInvalid($userId);
			$this->dieIfRelationIdInvalid($relationId);
			
			$this->discardRelationWithUserIdAndPrinMessage(
				(int) $relationId, (int) $userId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function discardRelationWithUserIdAndPrinMessage(
		int $relationId, 
		int $userId
	): void
	{
		$table = new UserTable();
		$logged = AuthFacade::getUser();
		$user = $table->find($userId);

		$this->discardRelation($user, $logged, $relationId);
		$this->printJsonResponseAndDie(
			sprintf('You have discarded relation with %s.', $user->login));
	}

	private function discardRelation(
		User $first, 
		User $second, 
		int $relationId
	): void
	{
		$table = new UserUserRelationTable();
		$table->discardRelation($first, $second, $relationId);
	}

	public function accept($userId, $relationId): void
	{
		try {
			$this->dieIfTokenInvalid(self::$tokenName);
			$this->dieIfUserIdInvalid($userId);
			$this->dieIfRelationIdInvalid($relationId);
			
			$this->acceptRelationWithUserAndPrintResponse(
				(int) $relationId, (int) $userId);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function acceptRelationWithUserAndPrintResponse(
		int $relationId, 
		int $userId
	): void
	{
		$table = new UserTable();
		$user = $table->find($userId);
		$current = AuthFacade::getUser();

		$table = new UserUserRelationTable();
		$table->acceptRelationRequestIfRequested($user, $current, $relationId);
		$this->printJsonResponseAndDie(
			'Request has been accepted. You are friends now!');
	}

	public function getRequests(): void
	{
		try {
			$this->printRelationRequestsForUser();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function printRelationRequestsForUser(): void
	{
		$user = AuthFacade::getUser();
		$table = new UserUserRelationTable();
		$requests = $table->getNotAcceptedRequestsForUser($user);

		die(json_encode($requests));
	}
}