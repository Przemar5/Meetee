<?php

namespace Meetee\App\Emails\Controllers;

use Meetee\App\Emails\Sendable;
use Meetee\App\Emails\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController implements Sendable
{
	public function send(?array $data = []): void
	{
		try {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->SMTPDebug = 1;
			$mail->SMTPAuth = true;
			$mail->Host = 'smtp.gmail.com';
			$mail->Subject = $data['subject'];
			$mail->Username = 'meetyea.app@gmail.com';
			$mail->Password = '1qaz@WSX#EDC';
			$mail->SMTPSecure = 'tls';
			$mail->setFrom('meetyea.app@gmail.com', 'Meetyea');
			$mail->addReplyTo('meetyea.app@gmail.com', 'Meetyea');
	
			foreach ($data['receivers'] as $user)
				$mail->addAddress($user->getEmail(), $user->getLogin());
	
			// $mail->content = '<h1>Test mail using PHPMailer</h1>';
			$mail->isHTML(true);
			$mail->ContentType = 'text/html';
			$mail->msgHTML($data['template']);

			if ($mail->send()) {
			    echo 'Message has been sent';
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