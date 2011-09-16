<?php
class Model_Meciuri extends Model_Abstract {
	function getMatches($page = null, $filters = array()) {
		$query = 'SELECT meci_id, meci_data_ora, meci_durata,
					e1.echipa_id echipa1_id, e2.echipa_id echipa2_id, 
					e1.echipa_nume echipa1_nume, e2.echipa_nume echipa2_nume,
					( SELECT COUNT(*) FROM Goluri WHERE meci_id = m.meci_id AND
						primaEchipa = 1) puncte1, 
						( SELECT COUNT(*) FROM Goluri WHERE meci_id = m.meci_id AND
						primaEchipa = 0) puncte2, sport_nume
				  FROM Meciuri m
					LEFT JOIN Echipe e1 ON (echipa_id1 = e1.echipa_id)
					LEFT JOIN Echipe e2 ON (echipa_id2 = e2.echipa_id)
					LEFT JOIN Sporturi s ON (m.sport_id = s.sport_id)
				  ORDER BY meci_data_ora DESC';
		$this->paginator( $query, $filters );
		
		return $this->db->fetchAll($query, $filters);
	}
	
	function getMatchDetails( $matchId = 0 ) {
		if (!$matchId = (int) $matchId)
			return null;
		return array(
			'matchInfo'	=> $this->db->fetchRow(
				'SELECT meci_data_ora, meci_durata, meci_id,
					e1.echipa_id echipa1_id, e2.echipa_id echipa2_id, 
					e1.echipa_nume echipa1_nume, e2.echipa_nume echipa2_nume,
					( SELECT COUNT(*) FROM Goluri WHERE meci_id = m.meci_id AND
						primaEchipa = 1) puncte1, 
						( SELECT COUNT(*) FROM Goluri WHERE meci_id = m.meci_id AND
						primaEchipa = 0) puncte2, sport_nume
				  FROM Meciuri m
					LEFT JOIN Echipe e1 ON (echipa_id1 = e1.echipa_id)
					LEFT JOIN Echipe e2 ON (echipa_id2 = e2.echipa_id)
					LEFT JOIN Sporturi s ON (e1.sport_id = s.sport_id)
				  WHERE meci_id = '. $matchId
			),
			'goalsInfo'	=> $this->db->fetchAll('SELECT jucator_nume, minutul, IF (	
				(j.echipa_id = echipa_id1 AND primaEchipa = 0)
					OR
				(j.echipa_id = echipa_id2 AND primaEchipa = 1), \'(AUTOGOL)\', \'\') autogol,
				gol_id
			FROM Goluri LEFT JOIN
				Meciuri USING (meci_id) 
				LEFT JOIN
				Jucatori j USING (jucator_id)
			WHERE meci_id = '.$matchId.'
			ORDER BY meci_data_ora DESC, minutul')
		);
	}
	
	function getTeamsForSport( $sport_id ) {
		return $this->db->fetchAll(
			'SELECT * FROM Echipe WHERE sport_id = ?', (int) $sport_id
		);
	}
	
	function getMatch( $id ) {
		return $this->db->fetchRow('SELECT * FROM Meciuri WHERE meci_id = ?', (int) $id);
	}
	
	function addGoal($data) {
		// fix for bit(1) in MySQL
		$data['primaEchipa'] = (boolean) $data['primaEchipa'];
		try {
			$this->db->insert('Goluri', $data);
			return true;
		} catch (Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function addGoalHelper( $matchId = 0 ) {
		if (!$matchId = (int) $matchId)
			return array();
		$info = array(
			'info'		=> $this->db->fetchRow(
				'SELECT echipa_id1, echipa_id2, e1.echipa_nume echipa_nume1, e2.echipa_nume echipa_nume2
				FROM Meciuri 
					LEFT JOIN Echipe e1 ON (echipa_id1 = e1.echipa_id)
					LEFT JOIN Echipe e2 ON (echipa_id2 = e2.echipa_id)
				WHERE meci_id = ?', $matchId
			)
		);
		$info['echipa1'] = $this->db->fetchAll(
				'SELECT jucator_id, jucator_nume FROM Jucatori WHERE echipa_id = ?', $info['info']['echipa_id1']
			);
		$info['echipa2'] = $this->db->fetchAll(
				'SELECT jucator_id, jucator_nume FROM Jucatori WHERE echipa_id = ?', $info['info']['echipa_id2']
			);
		return $info;
	}
	
	function deleteGoal($id) {
		try {
			$this->db->delete('Goluri', 'gol_id = '.(int)$id);
			return true;
		} catch ( Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function update( $data, $id = null ) {
		try {
			if ((int)$id) 
				$this->db->update('Meciuri', $data, 'meci_id = '.(int)$id);
			else {
				unset($data['meci_id']);
				$this->db->insert('Meciuri', $data);
			}
			return true;
		} catch(Zend_Db_Exception $e) {
			return false;
		}
	}
	
	function delete( $id ) {
		try {
			$this->db->delete('Meciuri', 'meci_id = '.(int)$id);
			return true;
		} catch (Zend_Db_Exception $e) {
			return false;
		}
	}
}