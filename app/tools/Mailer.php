<?php
/**
 * Static factory which provides creating already configured instances of mailers.
 *
 * @author Jan Papousek
 */
class Mailer
{

	private function  __construct() {}

	/**
	 * It returns configured instance of PHPMailer, which has 'From' field set
	 * on the Omnius info e-mail.
	 *
	 * @return PHPMailer
	 */
	public static function createPHPMailer() {
		// SMTP Authorization
		$config = Nette\Environment::getConfig("smtp");
		$mail = new PHPMailer();
		$mail->isMail();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = $config->host;
		$mail->Username = $config->username;
		$mail->Password = $config->password;
		// E-mail setting
		$mail->CharSet = "utf-8";
		$mail->IsHTML(TRUE);
		$mail->From = Nette\Environment::getConfig("mail")->info;
		$mail->FromName = "Interlos";

		return $mail;
	}

}
