<?php
class Controller_Echipe extends Controller_Abstract {
	protected $teams;
	function init() {
		$this->teams = new Model_Echipe;
	}
	
	function index() {
		$filters = array();
		if ((int) $_GET['sport_id']) {
			$filters['query'] = 'AND sport_id = ?';
			$filters[] = (int) $_GET['sport_id'];
		}
		
		$this->p['teams'] = $this->teams->getTeams($filters);
		$this->p['paginare'] = $this->teams->paginareHTML;
		
		$this->p['permissions']['update'] = $this->u->amIAllowedHere('/echipe/update');
		$this->p['permissions']['delete'] = $this->u->amIAllowedHere('/echipe/delete');
	}
	
	function update() {
		$this->u->amIAllowedHere();
		$errors = array();
		
		if ($_POST) {
			
			if (!$_POST['echipa_nume'])
				$errors['echipa_nume'] = 'Trebuie sa completati numele echipei!';
			if (!$_POST['echipa_nume_antrenor'])
				$errors['echipa_nume_antrenor'] = 'Trebuie sa completati numele antrenorului!';
				
			if (!count($errors)) {
				$this->teams->update($_POST, $_GET['id']);
				redirect('/echipe/index');
			}
		} else 
			$_POST = $this->teams->getTeam($_GET['id']);
		$this->p['errors'] = $errors;
		$sports = new Model_Sporturi;
		$this->p['sports'] = $sports->getSelect();
	}
	
	function delete() {
		$this->u->amIAllowedHere();
		$this->teams->delete( (int) $_GET['id'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
}