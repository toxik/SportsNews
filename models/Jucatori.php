<?php
class Model_Jucatori extends Model_Abstract {
	function getPlayers($filters = array()) {
		$query = 'SELECT *, DATE_FORMAT(NOW(), \'%Y\') - DATE_FORMAT(jucator_data_nasterii, \'%Y\') - (DATE_FORMAT(NOW(), \'00-%m-%d\') 
							< DATE_FORMAT(jucator_data_nasterii, \'00-%m-%d\')) AS age FROM Jucatori LEFT JOIN Echipe USING (echipa_id) 
						LEFT JOIN Sporturi USING (sport_id) WHERE 1 = 1 ';
		if ($filters['query']) {
			$query .= $filters['query'];
			unset($filters['query']);
		}
		$query .= ' ORDER BY jucator_nume';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
	
	function getPlayer( $id ) {
		return $this->db->fetchRow('SELECT * FROM Jucatori WHERE jucator_id = ?', (int) $id);
	}
	
	function update( $data, $id = null ) {
		try {
			if ((int)$id) 
				$this->db->update('Jucatori', $data, 'jucator_id = '.(int)$id);
			else {
				unset($data['jucator_id']);
				$this->db->insert('Jucatori', $data);
			}
			return true;
		} catch(Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function delete( $id ) {
		$this->db->delete('Jucatori', 'jucator_id = '.(int)$id);
	}
	
	function getGoalsByPlayerId( $player_id = 0 ) {
		if (!$player_id = (int) $player_id) 
			return null;
		$filters = array();
		$query = 'SELECT meci_id, jucator_nume, minutul, e1.echipa_nume e1nume, e2.echipa_nume e2nume, meci_data_ora, IF (	
				(j.echipa_id = echipa_id1 AND primaEchipa = 0)
					OR
				(j.echipa_id = echipa_id2 AND primaEchipa = 1), \'(AUTOGOL)\', \'\') autogol
			FROM Goluri LEFT JOIN
				Meciuri USING (meci_id) 
				LEFT JOIN
				Jucatori j USING (jucator_id)
				LEFT JOIN Echipe e1 ON ( echipa_id1 = e1.echipa_id )
				LEFT JOIN Echipe e2 ON ( echipa_id2 = e2.echipa_id )
			WHERE jucator_id = '.$player_id.'
			ORDER BY meci_data_ora DESC, minutul';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
}