<?php
// every controller will have in its $this->p the editable, global variable $page.
abstract class Controller_Abstract {
	protected $p, $s, $d, $u;
	function __construct(&$page) {
		$this->p = &$page;
		
		// conectarea la baza de date ?
		$dbInstance = Model_AbstractDb::getInstance();
		$this->d = $dbInstance->getDb();
		
		// verificam starea utilizatorului - daca este logat sau nu
		$authInstance = new Model_Auth;
		$this->u = $authInstance;
		
		// adaugam click-ul in baza de date
		$click = new Model_Click;
		$click->logAccess();
		
		//echo get_class();
		
		
		$this->init();
	}
	
	// functia obligatorie a fiecarui modul
	abstract function index();
	
	function init() {
		
	}
}