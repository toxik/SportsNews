<?php
class Model_Click extends Model_Abstract {
	protected $info = array();
	function init() {
		$auth = new Model_Auth;
		$browser = new Model_Browser;
		$this->info = array(
			'IP'		=> $this->getClientIp(),
			'REFERER'	=> $_SERVER['HTTP_REFERER'],
			'LOGGED_IN'	=> (boolean) $auth->isLoggedIn(),
			'URL'		=> $_SERVER['REQUEST_URI'],
			'BROWSER'   => $browser->getBrowser(). ' ' .$browser->getVersion()
		);
	}
	
	function logAccess() {
		$this->db->insert('Clicks', $this->info);
	}
	
	function getClientIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
			return $_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else 
			return $_SERVER['REMOTE_ADDR'];
	}
}