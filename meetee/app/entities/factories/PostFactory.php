<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\Factories\Factory;
use Meetee\App\Entities\Post; 
use Meetee\App\Entities\Comment;

class PostFactory implements Factory
{
	public function create(): Post;
} 