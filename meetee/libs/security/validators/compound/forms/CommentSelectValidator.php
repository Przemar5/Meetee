<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Utils\UnsignedIntValidator;
use Meetee\Libs\Security\Validators\Factories\ValidatorFactory;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentOnProfileValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentIdValidator;
use Meetee\Libs\Security\Validators\Compound\Users\UserIdValidator;
use Meetee\Libs\Security\Validators\Compound\Topics\TopicIdValidator;
use Meetee\Libs\Security\Validators\Compound\Groups\GroupIdValidator;

class CommentSelectValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'on_profile' => ValidatorFactory::createBoolValidator(),
		];

		$optional = [
			'topic_id' => new TopicIdValidator(),
			'group_id' => new GroupIdValidator(),
			'parent_id' => new CommentIdValidator(),
			'user_id' => new UserIdValidator(),
		];

		parent::__construct($validators, $optional);
	}
}