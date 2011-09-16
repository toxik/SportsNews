<?php
class Model_Utilizatori extends Model_Abstract {
	function getAllUsers($filters = array()) {
		$query = 'SELECT id, email, type, status
				  FROM Users WHERE 1=1 ';
		if ($filters['query']) {
			$query .= $filters['query'];
			unset($filters['query']);
		}
		$query .= ' ORDER BY type DESC, email';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
	
	function changeType( $user_id, $newType ) {
		try {
			$this->db->update('Users', array( 'type' => $newType ), 'id='. (int) $user_id );
			return true;
		} catch (Zend_Exception $e) {
			return false;
		}
	}
	
	function changeStatus( $user_id, $newStatus ) {
		try {
			$this->db->update('Users', array( 'status' => $newStatus ), 'id='. (int) $user_id );
			return true;
		} catch (Zend_Exception $e) {
			return false;
		}
	}
}