<?php

namespace Meetee\App\Emails;

use Meetee\Libs\View\ViewTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailAdapter
{
	public string $host;
	public string $username;
	public string $password;
	public string $encryption;
	public string $from;
	public string $fromName;
	public string $replyTo;
	public string $replyName;
	public array $receivers;
	public string $subject;
	public array $headers = [];
	protected ?string $template = null;
	protected ViewTemplate $view;

	public function __construct(ViewTemplate $view)
	{
		$this->view = $view;
	}

	public function parseTemplate(string $path, ?array $args = []): void
	{
		$this->template = $this->view->getRendered($path, $args);
	}

	public function send(): void
	{
		try {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->SMTPAuth = true;
			$mail->Host = $this->host;
			$mail->Subject = $this->subject;
			$mail->Username = $this->username;
			$mail->Password = $this->password;
			$mail->SMTPSecure = $this->encryption;
			$mail->setFrom($this->from, $this->fromName);
			$mail->addReplyTo($this->replyTo, $this->replyName);
	
			foreach ($this->receivers as $user)
				$mail->addAddress($user->email, $user->login);
	
			$mail->isHTML(true);
			$mail->ContentType = 'text/html';
			$mail->msgHTML($this->template);
			$mail->send();
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}