<?php
class Controller_Jucatori extends Controller_Abstract {
	protected $players;
	function init() {
		$this->players = new Model_Jucatori;
	}
	
	function index() {
		$filters = array();
		if ((int) $_GET['echipa_id']) {
			$filters['query'] = 'AND echipa_id = ?';
			$filters[] = (int) $_GET['echipa_id'];
		}
	
		$this->p['players'] = $this->players->getPlayers($filters);
		$this->p['paginare'] = $this->players->paginareHTML;
		
		$this->p['permissions']['update'] = $this->u->amIAllowedHere('/jucatori/update');
		$this->p['permissions']['delete'] = $this->u->amIAllowedHere('/jucatori/delete');
	}
	
	function update() {
		$this->u->amIAllowedHere();
		$errors = array();
		if ($_POST) {
			if (!$_POST['jucator_nume'])
				$errors['jucator_nume'] = 'Trebuie sa completati numele Jucatorului!';
			if (!$_POST['jucator_post'])
				$errors['jucator_post'] = 'Trebuie sa completati postul Jucatorului!';
			if (!$_POST['jucator_data_nasterii'])
				$errors['jucator_data_nasterii'] = 'Trebuie sa completati data nașterii Jucatorului!';
			if (!count($errors)) {
				$_POST['jucator_data_nasterii'] = formatData($_POST['jucator_data_nasterii'], true);
				$this->players->update($_POST, $_GET['id']);
				redirect('/jucatori/index');
			}
		} else {
			$_POST = $this->players->getPlayer( $_GET['id'] );
			// stabilim doar la insert echipa. daca o schimba, se sterge si se reinsereaza
			if ($_GET['echipa_id'] && !$_POST['echipa_id'])
				$_POST['echipa_id'] = $_GET['echipa_id'];
		}
		$this->p['errors'] = $errors;
	}
	
	function delete() {
		$this->u->amIAllowedHere();
		$this->players->delete( (int) $_GET['id'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function goluri() {
		if (!$_GET['jucator_id'] = (int) $_GET['jucator_id']) 
			exit('NO ACCESS.');

		$this->p['goals'] = $this->players->getGoalsByPlayerId($_GET['jucator_id']);
	}
}