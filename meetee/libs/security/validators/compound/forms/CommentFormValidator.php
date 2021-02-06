<?php

namespace Meetee\Libs\Security\Validators\Compound\Forms;

use Meetee\Libs\Security\Validators\Compound\FormValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentBodyValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentOnProfileValidator;
use Meetee\Libs\Security\Validators\Compound\Comments\CommentIdValidator;
use Meetee\Libs\Security\Validators\Compound\Topics\TopicIdValidator;
use Meetee\Libs\Security\Validators\Compound\Groups\GroupIdValidator;

class CommentFormValidator extends FormValidator
{
	public function __construct()
	{
		$validators = [
			'content' => new CommentBodyValidator(),
			'on_profile' => new CommentOnProfileValidator(),
		];

		$optional = [
			'topic_id' => new TopicIdValidator(),
			'group_id' => new GroupIdValidator(),
			'parent_id' => new CommentIdValidator(),
		];

		parent::__construct($validators, $optional);
	}
}