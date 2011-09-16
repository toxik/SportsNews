<?php
class Model_Echipe extends Model_Abstract {
	function getTeams($filters = array()) {
		$query = 'SELECT * FROM Echipe LEFT JOIN Sporturi USING (sport_id) WHERE 1=1 ';
		if ($filters['query']) {
			$query .= $filters['query'];
			unset($filters['query']);
		}
		$query .= ' ORDER BY echipa_nume, sport_nume';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
	
	function getTeam($id) {
		return $this->db->fetchRow('SELECT * FROM Echipe WHERE echipa_id = ?', (int) $id);
	}
	
	function update( $data, $id = null ) {
		try {
			if ((int)$id) 
				$this->db->update('Echipe', $data, 'echipa_id = '.(int)$id);
			else {
				unset($data['echipa_id']);
				$this->db->insert('Echipe', $data);
			}
			return true;
		} catch(Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function delete( $id ) {
		$this->db->delete('Echipe', 'echipa_id = '.(int)$id);
	}
	
	
	function getSelect() {
		return $this->db->getPairs('SELECT echipa_id, echipa_nume ORDER BY echipa_nume');
	}
}