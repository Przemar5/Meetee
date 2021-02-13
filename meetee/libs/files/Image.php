<?php

namespace Meetee\Libs\Files;

class Image
{
	private const BASE_DIR = './public/images/users/';
	private array $image;
	private array $size;

	public function __construct(string $name)
	{
		$this->image = $_FILES[$name];
		$this->size = getimagesize($this->image['tmp_name']);
	}

	public function upload(): bool
	{
		$fileExt = ltrim(image_type_to_extension($this->size[2]), '.');
		$extensions = ['jpeg', 'jpg', 'png'];

		if (!in_array($fileExt, $extensions) || !$this->size || 
			$this->image['error'] || $this->image['size'] > 2097152)
			return;
		
		[$usec, $sec] = explode(' ', microtime());
		$time = $sec . explode('.', $usec)[1];

		$targetDir = self::BASE_DIR . $time . rand(100000, 999999) . $fileExt;

		move_uploaded_file($this->image['tmp_name'], $targetDir);
	}
}