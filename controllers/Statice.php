<?php
class Controller_Statice extends Controller_Abstract {
	function init() {
		$this->p['cssf'][] = 'statice';
	}
	function index() { }
	function contact() { 
		if ($_POST) {	
			require 'inc/recaptchalib.php';
			$errors = array();
			if (!$_POST['recaptcha_response_field'])
				$errors['captcha'] = 'Nu ati completat campul de control!';
			else if (!recaptcha_check_answer( 
						RECAPTCHA_PRV, $_SERVER['REMOTE_ADDR'],
						$_POST['recaptcha_challenge_field'],
                        $_POST['recaptcha_response_field']
                      )->is_valid
				)
				$errors['captcha'] = 'Codul completat nu a fost valid!';
			if (!$_POST['mesaj'])
				$errors['mesaj'] = 'Trebuie sa completati mesajul!';
			if (!$_POST['mail'])
				$errors['mail'] = 'Trebuie sa completati campul email!';
			else 
				if (!Zend_Validate::is($_POST['mail'], 'EmailAddress', array('mx' => true)))
					$errors['mail'] = 'Adresa de email nu este corecta!';
			
			if (!count($errors)) {			
				$mail = new Zend_Mail('UTF-8');
				$mail->addBcc('alex@navigheaza.ro');
				$mail->addTo($_POST['mail']);
				$mail->setReplyTo($_POST['mail']);
				$mail->setSubject('Mesaj de contact de pe php.toxik.us');
				$mail->setBodyText('Subiect: '. $_POST['subiect'] . "\r\n" .
								   'Mesaj: ' . "\r\n" . $_POST['mesaj']);
				$mail->send();
			}
			$this->p['errors'] = $errors;
		}
	}
}