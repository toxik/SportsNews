<?php
class Controller_Auth extends Controller_Abstract {

	function init() {
		$this->p['loggedIn'] = $this->u->isLoggedIn();
	}
	
	function index() {
		// i DUNOOO
	}
	
	function login() {
		if($_POST && !$this->u->isLoggedIn()) {
			if( USE_RECAPTCHA ) {
				require_once 'inc/recaptchalib.php';
				if (!$_POST['recaptcha_response_field'])
					$errors['captcha'] = 'Nu ati completat campul de control!';
				else if (!recaptcha_check_answer( 
							RECAPTCHA_PRV, $_SERVER['REMOTE_ADDR'],
							$_POST['recaptcha_challenge_field'],
							$_POST['recaptcha_response_field']
						  )->is_valid
					)
					$errors['captcha'] = 'Codul completat nu a fost valid!';
			}
			if (!Zend_Validate::is($_POST['email'], 'EmailAddress', array('mx' => true)))
				$errors['email'] = 'Adresa de email nu este corecta!';
			if (!$_POST['pass'])
				$errors['pass'] = 'Trebuie sa completati parola!';
		
			if (!count($errors))
				$this->p['login'] = $this->u->login($_POST['email'], $_POST['pass']);
			else 
				$this->p['errors'] = $errors;
		}
	}
	
	function register() {
		// se apeleaza modelul, se auto-logheaza si apoi redirect la pagina lui
		if ($this->u->isLoggedIn()) 
			redirect($_SERVER['HTTP_REFERER']);
		if ($_POST) {
			if( USE_RECAPTCHA ) {
				require_once 'inc/recaptchalib.php';
				if (!$_POST['recaptcha_response_field'])
					$errors['captcha'] = 'Nu ati completat campul de control!';
				else if (!recaptcha_check_answer( 
							RECAPTCHA_PRV, $_SERVER['REMOTE_ADDR'],
							$_POST['recaptcha_challenge_field'],
							$_POST['recaptcha_response_field']
						  )->is_valid
					)
					$errors['captcha'] = 'Codul completat nu a fost valid!';
			}
			if (!Zend_Validate::is($_POST['email'], 'EmailAddress', array('mx' => true)))
				$errors['email'] = 'Adresa de email nu este corecta!';
			if (!$_POST['password'])
				$errors['password'] = 'Trebuie sa completati parola!';
			if ($_POST['password2'] != $_POST['password'])
				$errors['password2'] = 'Parolele nu coincid!';
			if (!count($errors)) {
				Zend_Debug::dump($this->u->register($_POST['email'], $_POST['password'], 'U'));
			} else 
				$this->p['errors'] = $errors;
		}
	}
	
	function logout() {
		// se apeleaza modelul si apoi redirect la prima pagina
		$this->u->logout();
		redirect('/');
	}
}