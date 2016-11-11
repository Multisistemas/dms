<?php
/**
 * Implementation of notifation system using email
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010 Uwe Steinmann
 * @version    Release: @package_version@
 */

/**
 * Include parent class
 */
require_once("inc.ClassNotify.php");
require_once("Mail.php");
require_once("PHPMailer/PHPMailerAutoload.php");
require_once("PHPMailer/class.smtp.php");


/**
 * Class to send email notifications to individuals or groups
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_EmailNotify extends SeedDMS_Notify {
	/**
	 * Instanz of DMS
	 */
	protected $_dms;

	protected $smtp_server;

	protected $smtp_port;

	protected $smtp_user;

	protected $smtp_password;

	protected $from_address;

	function __construct($dms, $from_address='', $smtp_server='', $smtp_port='', $smtp_username='', $smtp_password='') { /* {{{ */
		$this->_dms = $dms;
		$this->smtp_server = $smtp_server;
		$this->smtp_port = $smtp_port;
		$this->smtp_user = $smtp_username;
		$this->smtp_password = $smtp_password;
		$this->from_address = $from_address;
	} /* }}} */

	/**
	 * Send mail to individual user
	 *
	 * @param mixed $sender individual sending the email. This can be a
	 *        user object or a string. If it is left empty, then
	 *        $this->from_address will be used.
	 * @param object $recipient individual receiving the mail
	 * @param string $subject key of string containing the subject of the mail
	 * @param string $message key of string containing the body of the mail
	 * @param array $params list of parameters which replaces placeholder in
	 *        the subject and body
	 * @return false or -1 in case of error, otherwise true
	 */
	function toIndividual($sender, $recipient, $subject, $message, $params=array()) { /* {{{ */
		/*if ($recipient->isDisabled() || $recipient->getEmail()=="") return 0;

		if(!is_object($recipient) || strcasecmp(get_class($recipient), $this->_dms->getClassname('user'))) {
			return -1;
		}

		if(is_object($sender) && !strcasecmp(get_class($sender), $this->_dms->getClassname('user'))) {
			$from = $sender->getFullName() ." <". $sender->getEmail() .">";
		} elseif(is_string($sender) && trim($sender) != "") {
			$from = $sender;
		} else {
			$from = $this->from_address;
		}

		$lang = $recipient->getLanguage();

		$message = getMLText("email_header", array(), "", $lang)."\r\n\r\n".getMLText($message, $params, "", $lang);
		$message .= "\r\n\r\n".getMLText("email_footer", array(), "", $lang);

		$headers = array ();
		$headers['From'] = $from;
		$headers['To'] = $recipient->getEmail();
		$headers['Subject'] = getMLText($subject, $params, "", $lang);
		$headers['MIME-Version'] = "1.0";
		$headers['Content-type'] = "text/plain; charset=utf-8";

		$mail_params = array();
		if($this->smtp_server) {
			$mail_params['host'] = $this->smtp_server;
			if($this->smtp_port) {
				$mail_params['port'] = $this->smtp_port;
			}
			if($this->smtp_user) {
				$mail_params['auth'] = true;
				$mail_params['username'] = $this->smtp_user;
				$mail_params['password'] = $this->smtp_password;
			}
			//$mail = Mail::factory('smtp', $mail_params);

			$mail = new PHPMailer();

			$mail->isSMTP();

			$mail->Host = $mail_params['host'];
			$mail->Port = 587;
			$mail->From = $headers['From'];
			$mail->FromName = "Servicio de envio automatico";
			$mail->Subject = $headers['Subject'];
			$mail->MsgHTML($message);
			$mail->AddAddress($headers['To']);
			$mail->SMTPAuth = true;
			$mail->Username = $mail_params['username'];
			$mail->Password = $mail_params['password'];

			$mail->SMTPDebug = 1;
			$mail->Debugoutput = 'html';

			var_export($mail);
		} /*else {
			$mail = Mail::factory('mail', $mail_params);
		}*/
 
		/*$result = $mail->send($recipient->getEmail(), $headers, $message);
		if (PEAR::isError($result)) {
			return false;
		} else {
			return true;
		}

		if (!$mail->Send()){
			debug_to_console("Mailer Error: " . $mail->ErrorInfo);
			return false;
		} else {
			debug_to_console("Message sent!");
			return true;
		}*/

		$mail             = new PHPMailer();
		$body             = "Este es un mensaje de prueba";

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "obsidian.websitewelcome.com"; // SMTP server
		$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "obsidian.websitewelcome.com"; // sets the SMTP server
		$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
		$mail->Username   = "test@gestiontotal.net"; // SMTP account username
		$mail->Password   = "[Fq5AG99=w#@";        // SMTP account password

		$mail->SetFrom('test@gestiontotal.net', 'First Last');

		$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($body);

		$address = "lmedrano@multisistemas.com.sv";
		$mail->AddAddress($address, "Luis Medrano");

		$mail->Debugoutput = 'html';
		var_export($mail);

		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		  return false;
		} else {
		  echo "Message sent!";
		  return true;
		}
    
	 }/* }}} */


	function toGroup($sender, $groupRecipient, $subject, $message, $params=array()) { /* {{{ */
		if ((!is_object($sender) && strcasecmp(get_class($sender), $this->_dms->getClassname('user'))) ||
				(!is_object($groupRecipient) || strcasecmp(get_class($groupRecipient), $this->_dms->getClassname('group')))) {
			return -1;
		}

		foreach ($groupRecipient->getUsers() as $recipient) {
			$this->toIndividual($sender, $recipient, $subject, $message, $params);
		}

		return true;
	} /* }}} */

	function toList($sender, $recipients, $subject, $message, $params=array()) { /* {{{ */
		if ((!is_object($sender) && strcasecmp(get_class($sender), $this->_dms->getClassname('user'))) ||
				(!is_array($recipients) && count($recipients)==0)) {
			return -1;
		}

		foreach ($recipients as $recipient) {
			$this->toIndividual($sender, $recipient, $subject, $message, $params);
		}

		return true;
	} /* }}} */
}
?>
