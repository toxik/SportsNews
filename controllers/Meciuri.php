<?php
class Controller_Meciuri extends Controller_Abstract {
	function init() {
		$this->matches = new Model_Meciuri;
	}
	
	function index() {
		$this->p['matches'] = $this->matches->getMatches();
		$this->p['paginare'] = $this->matches->paginareHTML;
		
		$this->p['permissions']['update'] = $this->u->amIAllowedHere('/meciuri/update');
		$this->p['permissions']['delete'] = $this->u->amIAllowedHere('/meciuri/delete');
	}
	
	function detalii() {
		if (!$_GET['meci_id'] = (int) $_GET['meci_id'])
			exit('NO ACCESS.');
		$this->p['details'] = $this->matches->getMatchDetails($_GET['meci_id']);
		
		$this->p['permissions']['addGoal'] = $this->u->amIAllowedHere('/meciuri/addGoal');
		$this->p['permissions']['deleteGoal'] = $this->u->amIAllowedHere('/meciuri/deleteGoal');
	}
	
	function update() {
		$this->u->amIAllowedHere();
		$sports = new Model_Sporturi;
		$errors = array();
		if ($_POST) {
			$_POST['meci_data'] = formatData( $_POST['meci_data'], true );
			if (!$_POST['meci_data'] || !$_POST['meci_ora_minutul'])
				$errors['echipa_nume'] = 'Trebuie sa completati data si ora!';
				
			if (!count($errors)) {
				$_POST['meci_data_ora'] .= $_POST['meci_data'] .
							' '.$_POST['meci_ora_minutul'] . ':00';
				unset( $_POST['meci_data'] ); unset( $_POST['meci_ora_minutul'] );
				$this->matches->update($_POST, $_GET['id']);
				redirect('/matches/index');
			}
		} else {
			$_POST = $this->matches->getMatch( $_GET['id'] );
			$_POST['meci_data'] = substr($_POST['meci_data_ora'],0,10);
			$_POST['meci_ora_minutul'] = substr($_POST['meci_data_ora'],11,5);
		}
		
		
		$this->p['sports'] = $sports->getSelect();
		$this->p['teams'] = $this->matches->getTeamsForSport($_POST['sport_id']);
		$this->p['errors'] = $errors;
	}
	
	function getRawTeams() {
		if (!$_GET['sport_id'] = (int) $_GET['sport_id'])
			exit;
		$this->p['single'] = true;
		$this->p['teams'] = $this->matches->getTeamsForSport($_GET['sport_id']);
	}
	
	function addGoal() {
		$this->u->amIAllowedHere();
		$this->p['data'] = $this->matches->addGoalHelper( (int) $_GET['meci_id'] );
		if (($_POST['minutul'] = (int) $_POST['minutul']) && 
			($_GET['meci_id'] = (int) $_GET['meci_id']) ) {
			$_POST['meci_id'] = $_GET['meci_id'];
			$this->matches->addGoal($_POST);
			redirect('/meciuri/detalii/meci_id/' . $_GET['meci_id']);
		}
		
	}
	
	function delete() {
		$this->u->amIAllowedHere();
		$this->matches->delete( (int) $_GET['id'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function deleteGoal() {
		$this->u->amIAllowedHere();
		$this->matches->deleteGoal( (int) $_GET['id'] );
		redirect($_SERVER['HTTP_REFERER']);
	}
	
}