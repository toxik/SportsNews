<?php
class Model_Sporturi extends Model_Abstract {
	function getSports($page = null, $filters = array()) {
		$query = 'SELECT * FROM Sporturi ORDER BY sport_nume';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
	
	function getSport( $id ) {
		return $this->db->fetchRow('SELECT * FROM Sporturi WHERE sport_id = ?', (int) $id );
	}
	
	function getSelect() {
		return $this->db->fetchPairs(
			'SELECT sport_id, sport_nume FROM Sporturi ORDER BY sport_nume'
		);
	}
	
	function update( $data, $id = null ) {
		try {
			if ($int = (int) $id) 
				$this->db->update('Sporturi', $data, 'sport_id = '. $id);
			else {
				unset($data['sport_id']);
				$this->db->insert('Sporturi', $data);
			}
			return true;
		} catch(Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function delete( $id ) {
		$this->db->delete('Sporturi', 'sport_id = '.(int)$id);
	}
}