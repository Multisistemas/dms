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
/*require_once("PHPMailer/PHPMailerAutoload.php");*/
require_once("PHPMailer/class.phpmailer.php");
require_once("PHPMailer/PHPMailerAutoload.php");

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
		if ($recipient->isDisabled() || $recipient->getEmail()=="") return 0;

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

		$mail = new PHPMailer;
		
		$to = $recipient->getEmail();
		$toName = $recipient->getFullName();
		
		$mail->setFrom($from, 'Multisistemas DMS');
		$mail->addAddress($to, $toName);
		$mail->set('Subject', getMLText($subject, $params, "", $lang));
		$mail->msgHTML($message);
		
		$headers = array ();
		$headers['From'] = $from;
		$headers['To'] = $recipient->getEmail();
		$headers['Subject'] = getMLText($subject, $params, "", $lang);
		$headers['MIME-Version'] = "1.0";
		$headers['Content-type'] = "text/plain; charset=utf-8";

		if($this->smtp_server) {
			$mail->Host = $this->smtp_server;
			if($this->smtp_port) {
				$mail->Port = $this->smtp_port;
			}
			if($this->smtp_user) {
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'tls';
				$mail->Username = $this->smtp_user;
				$mail->Password = $this->smtp_password;
			}
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Debugoutput = 'html';
		} else {
//			$mail = Mail::factory('mail', $mail_params);
		}

		if (!$mail->send()) {
			debug_to_console("Mailer Error: " . $mail->ErrorInfo);
			return false;
		} else {
			debug_to_console("Message sent!");
			return true;
		}
	} /* }}} */

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
