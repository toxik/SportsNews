<?php
class Controller_Sporturi extends Controller_Abstract {
	function init() {
		$this->sports = new Model_Sporturi;
	}
	
	function index() {		
		$this->p['sports'] = $this->sports->getSports();
		$this->p['paginare'] = $this->sports->paginareHTML;
		
		$this->p['permissions']['update'] = $this->u->amIAllowedHere('/sporturi/update');
		$this->p['permissions']['delete'] = $this->u->amIAllowedHere('/sporturi/delete');
	}
	
	function update() {
		$this->u->amIAllowedHere();
		$errors = array();
		if ($_POST) {
			if (!$_POST['sport_nume'])
				$errors['sport_nume'] = 'Trebuie sa completati numele sportului!';
			if (!count($errors)) {
				$this->sports->update($_POST, $_GET['id']);
				redirect('/sporturi/index');
			}
		} else 
			$_POST = $this->sports->getSport($_GET['id']);
		$this->p['errors'] = $errors;
	}
	
	function delete() {
		$this->u->amIAllowedHere();
		$this->sports->delete( (int) $_GET['id'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
	
}