<?php

namespace Meetee\Libs\Files\Uploaders;

class FileUploader
{
	protected array $file;
	protected array $info;
	protected array $extensions;
	protected string $baseDirectory;
	protected string $filename;
	protected int $maxSize = 2097152;
	protected bool $error = false;

	public function upload(string $name, string $path, ?string $filename = null): void
	{
		$this->file = $_FILES[$name];
		$this->info = getimagesize($this->file['tmp_name']);
		$this->error = false;
		$fileExt = ltrim(image_type_to_extension($this->info[2]), '.');

		if (!in_array($fileExt, $this->extensions) || !$this->info || 
			$this->file['error'] || $this->file['size'] > $this->maxSize) {
			$this->error = true;
			return;
		}

		$filename = (empty($filename)) 
			? $this->generateRandomFilename($fileExt) : $filename;
		$this->filename = $path . $filename;

		move_uploaded_file($this->file['tmp_name'], 
			$this->baseDirectory . $this->filename);
	}

	public function generateRandomFilename(string $extension): string
	{
		[$usec, $sec] = explode(' ', microtime());
		$time = $sec . explode('.', $usec)[1];
		
		return $time . rand(100000, 999999) . '.' . $extension;
	}

	public function setExtensions(array $extensions): void
	{
		$this->extensions = $extensions;
	}

	public function setBaseDirectory(string $directory): void
	{
		$this->baseDirectory = $directory;
	}

	public function setMaxSize(int $maxSize): void
	{
		$this->maxSize = $maxSize;
	}

	public function hasError(): bool
	{
		return $this->error;
	}

	public function getFilename(): ?string
	{
		return $this->filename;
	}
}