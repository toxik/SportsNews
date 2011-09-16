<?php
class Controller_Utilizatori extends Controller_Abstract {
	function init() {
		$this->users = new Model_Utilizatori;
	}
	
	function index() {
		$this->u->amIAllowedHere();
		$this->p['users'] = $this->users->getAllUsers();
		$this->p['paginare'] = $this->matches->paginareHTML;
	}
	
	function changeStatus() {
		$this->u->amIAllowedHere();
		$this->users->changeStatus( (int) $_GET['id'], (int) $_GET['s'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function changeType() {
		$this->u->amIAllowedHere();
		$this->users->changeType( (int) $_GET['id'], $_GET['t'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
}