<?php
class Model_Abstract {
	protected $db;
	public $paginare = null;
	public $paginareHTML;
	function __construct() {
		$dbInstance = Model_AbstractDb::getInstance();
		$this->db = $dbInstance->getDb();
		
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		Zend_Paginator::setDefaultItemCountPerPage(RESULTS_PER_PAGE);
		$this->init();
	}
	
	function init() { }
	
	function paginator(&$query, &$params, $p = null) {
		if ($p === null) 
			$p = $_GET['p'];
		$p = !empty($p) ? (int) ($p - 1) : 0;
		
		// do a "head count" - cate rezultate trebuiesc paginate
		$nrRezultate = $this->db->fetchOne('SELECT COUNT(*) as nrRezultate FROM ('.$query.') tabelBaza', $params );
		
		//$nrRezultate = 10000;
		
		$this->paginare = Zend_Paginator::factory($this->create_array($nrRezultate));
		$this->paginare->setCurrentPageNumber($p+1);
		$this->paginare = $this->paginare->getPages();
		
		$query .= ' LIMIT ?,?';
		$params[] = $p * RESULTS_PER_PAGE;
		$params[] = RESULTS_PER_PAGE;
		
		include 'views/paginatie.phtml';
		
		$this->paginareHTML = ob_get_clean();
		
		return $p;
	}
	
	protected function create_array($no_items = 0) {
		$array = array();
		for ($i =0 ; $i< $no_items; $i++)
			$array[] = false;
		return $array;
	}
}