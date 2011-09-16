<?php
class Model_Statistici extends Model_Abstract {
	function parseDates($start, $end) {
		$start = "STR_TO_DATE(".$this->db->quote($start).", GET_FORMAT(DATE,'EUR'))";
		if (!$end)
			$end = 'NOW()';
		else
			// sa luam toate statisticile pentru acea zi
			$end = "DATE_ADD(STR_TO_DATE(".$this->db->quote($end).", GET_FORMAT(DATE, 'EUR')), INTERVAL 1 DAY)";
		return array($start, $end);
	}

	function getAccesari($start, $end = null, $onlyLoggedIn = false) {
	
		list($start, $end) = $this->parseDates($start,$end);
		
		return $this->prelucrare($this->db->fetchPairs(
			"SELECT UNIX_TIMESTAMP(LEFT(TIMESTAMP, 10)), COUNT(*)
			FROM Clicks 
			WHERE TIMESTAMP BETWEEN $start AND $end " .
			( $onlyLoggedIn ? ' AND LOGGED_IN <> 0 ' : '' ) .
			"GROUP BY LEFT(TIMESTAMP, 10)
			ORDER BY 1 ASC"
		));
	}
	
	function getUnici($start, $end = null, $onlyLoggedIn = false) {
		list($start, $end) = $this->parseDates($start,$end);
		
		return $this->prelucrare($this->db->fetchPairs(
			"SELECT UNIX_TIMESTAMP(LEFT(TIMESTAMP, 10)) ziua, COUNT(DISTINCT(IP))  
			FROM Clicks 
			WHERE TIMESTAMP BETWEEN $start AND $end " .
			( $onlyLoggedIn ? ' AND LOGGED_IN <> 0 ' : '' ) .
			"GROUP BY LEFT(TIMESTAMP, 10)
			ORDER BY 1 ASC"
		));
	}
	
	function getTopXPages($start, $end = null, $x = 10) {
		list($start, $end) = $this->parseDates($start,$end);
		
		return $this->db->fetchAll(
			"SELECT COUNT(URL) total, URL
			FROM Clicks
			WHERE URL IS NOT NULL AND 
				TIMESTAMP BETWEEN $start AND $end
			GROUP BY URL
			ORDER BY 1 DESC
			LIMIT ?", $x
		);
	}
	
	function getTopXUserAgents($start, $end = null, $x = 10) {
		list($start, $end) = $this->parseDates($start,$end);
		
		return $this->db->fetchAll(
			"SELECT COUNT(BROWSER) total, BROWSER
			FROM Clicks
			WHERE BROWSER IS NOT NULL AND 
				TIMESTAMP BETWEEN $start AND $end
			GROUP BY BROWSER
			ORDER BY 1 DESC
			LIMIT ?", $x
		);
	}
	
	function getTopXReferrers($start, $end = null, $x = 10) {
		list($start, $end) = $this->parseDates($start,$end);
		
		return $this->db->fetchAll(
			"SELECT COUNT(REFERER) total, REFERER
			FROM Clicks
			WHERE REFERER IS NOT NULL AND 
				TIMESTAMP BETWEEN $start AND $end
			GROUP BY REFERER
			ORDER BY 1 DESC
			LIMIT ?", $x
		);
	}
	
	function getRawData($start, $end) {
		list($start, $end) = $this->parseDates($start, $end);
		return $this->db->fetchAll(
			"SELECT * FROM Clicks WHERE TIMESTAMP BETWEEN $start AND $end"
		);
	}
	
	protected function prelucrare( $data ) {
		// inseram zilele goale :D
		$i = 0;
		foreach($data as $ts => $value) {
			if (!$i++)
				$start = $ts;
			$end = $ts;
		}
		// 86400 = nr secunde intr-o zi
		for($i = $start; $i < $end; $i+= 86400)
			if (!$data[$i])
				$data[$i] = 0;
		
		ksort($data);
		return $data;
	}
}