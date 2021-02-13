<?php

namespace Meetee\Libs\Files\Uploaders;

use Meetee\Libs\Files\Uploaders\FileUploader;

class ImageUploader extends FileUploader
{
	protected ?string $profilePhotoFilename = null;
	protected string $defaultProfileFilename = 'users/noimage.png';

	public function validateAndUploadImage(string $name, string $path = ''): bool
	{
		[$usec, $sec] = explode(' ', microtime());
		$time = $sec . explode('.', $usec)[1];
		$uploader = new FileUploader();
		$uploader->setExtensions(['jpeg', 'jpg', 'png']);
		$baseDir = './' . substr(IMG_DIR, strcmp(BASE_URI, IMG_DIR));
		$uploader->setBaseDirectory($baseDir);
		$uploader->upload($name, $path);
		$this->profilePhotoFilename = $uploader->getFilename();
		
		return !$uploader->hasError();
	}

	public function getProfilePhotoFilename(): ?string
	{
		return $this->profilePhotoFilename;
	}
}