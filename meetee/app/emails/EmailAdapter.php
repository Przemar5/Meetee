<?php

namespace Meetee\App\Emails;

use Meetee\Libs\View\ViewTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailAdapter
{
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
			$mail->SMTPDebug = 1;
			$mail->SMTPAuth = true;
			$mail->Host = 'smtp.gmail.com';
			$mail->Subject = $this->subject;
			$mail->Username = 'meetyea.app@gmail.com';
			$mail->Password = '1qaz@WSX#EDC';
			$mail->SMTPSecure = 'tls';
			$mail->setFrom('meetyea.app@gmail.com', 'Meetyea');
			$mail->addReplyTo('meetyea.app@gmail.com', 'Meetyea');
	
			foreach ($this->receivers as $user)
				$mail->addAddress($user->email, $user->login);
	
			$mail->isHTML(true);
			$mail->ContentType = 'text/html';
			$mail->msgHTML($this->template);

			if ($mail->send()) {
			    echo 'Message has been sent';
			    die;
			}
			else {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}