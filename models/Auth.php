<?php
class Model_Auth extends Model_Abstract {
	private $adapter;
	private $auth;
	private $acl;
	
	function init() {
		$this->adapter = new Zend_Auth_Adapter_DbTable(
			$this->db,
			'Users',
			'email',
			'password',
			'MD5(?) AND status = 1'
		);
		$this->auth = Zend_Auth::getInstance();
		
		// definirea permisiunilor in aplicatie
		/*
			u -> user, m -> manager, a -> administrator
		*/
		$this->acl = new Zend_Acl();
		$this->acl->addRole(new Zend_Acl_Role('U')) // user-ul
				->addRole(new Zend_Acl_Role('M', 'U')) // manager-ul, care mosteneste user-ul
				->addRole(new Zend_Acl_Role('A')) // administratorul 
				->allow('U', null, array( '/meciuri/update', '/meciuri/delete', '/statistici',
										'/meciuri/addGoal', '/meciuri/deleteGoal' )
						)
				->allow('M', null, array( '/sporturi/update', '/sporturi/delete', 
										'/echipe/update', '/echipe/delete',  
										'/jucatori/update', '/jucatori/delete' )
						)
				->allow('A'); // adminul are voie oriunde
				
	}
	function login($email, $pass) {
		$this->adapter->setIdentity($email);
		$this->adapter->setCredential($pass);
		$result = $this->auth->authenticate($this->adapter);
		
		if ($result->isValid()) {
            $user = $this->adapter->getResultRowObject();
            $this->auth->getStorage()->write($user);
            return true;
        }
        return false;
	}
	
	function isLoggedIn() {
        if ($this->auth->hasIdentity())
            return $username = $this->auth->getIdentity();
		return false;
	}
	
	function getUserDetails() {
		return $this->auth->getStorage()->read();
	}
	
	function amIAllowedHere( $where = null ) {
		$type = $this->getUserDetails();
			$type = $type->type;
		
		if (!$where) {
			// verificam de unde vine requestul 
			$caller = debug_backtrace();
			$caller = $caller[1];
			$caller = '/' . strtolower(substr($caller['class'], 11)). '/' . $caller['function'];
		} else 
			$caller = $where;
			
		$access = $this->acl->isAllowed($type, null, $caller) ? true : false;
		
		if ( !$access && !$where ) {
			header('HTTP/1.1 403 Forbidden');
			exit('Accesul dvs. la aceasta resursa este interzis!');
		}
		return $access;
	}
	
	function logout() {
		return $this->auth->clearIdentity();
	}
	
	function register($email, $pass, $type = 'U'){
		$errors = array();
		
		if (!count($errors)) {
			$data = array(
					'email' => $email,
					'password' => new Zend_Db_Expr( $this->db->quoteInto('MD5( ? )', $pass) ),
					'type' => $type
				);
			try {
				$this->db->insert('Users',$data);
				$this->login($email, $pass);
				
				$mail = new Zend_Mail('UTF-8');
				$mail->addTo($email);
				$mail->setReplyTo($email);
				$mail->setSubject('Cont nou pe php.toxik.us');
				$mail->setBodyText('Bine ati venit! Ati creat cu succes un cont pe php.toxik.us! '. 
									"\r\n" . 'Parola dvs este ' . "\r\n" . $pass);
				$mail->send();
				
				redirect('/');
			} catch(Zend_Db_Exception $e) {
				// inseamna ca exista deja username-ul
				return array('errors' => array('Emailul exista deja in baza de date! ' . $e->getMessage()));
			}
		} else 
		return array('errors' => $errors);
		
		$data['id'] = $this->db->lastInsertId();
		return array('success' => $data);
	}
}