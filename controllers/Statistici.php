<?php
class Controller_Statistici extends Controller_Abstract {
	function init() {
		if (!$_GET['start']) 
			// setam data de inceput cu o saptamana in urma
			$_GET['start'] = date('d.m.Y', strtotime('-7 days'));
		if ($_GET['end'] == 'acum' || !$_GET['end'])
			$_GET['end'] = date('d.m.Y');
	}
	function index() {
		$s = new Model_Statistici;
		
		$this->p = array(
			'accesari' 			=> $s->getAccesari($_GET['start'], $_GET['end']),
			'accesariLoggedIn' 	=> $s->getAccesari($_GET['start'], $_GET['end'], true),
			'unici'				=> $s->getUnici($_GET['start'], $_GET['end']),
			'uniciLoggedIn'		=> $s->getUnici($_GET['start'], $_GET['end'], true),
			'top10Pages'		=> $s->getTopXPages($_GET['start'], $_GET['end']),
			'top10Browsers'		=> $s->getTopXUserAgents($_GET['start'], $_GET['end']),
			'top10Referrers'	=> $s->getTopXReferrers($_GET['start'], $_GET['end'])
		);
	}
	
	function raport() {
		$s = new Model_Statistici;
		require_once 'inc/excel.php';
		
		$locatie = realpath(dirname(__FILE__).'/../../rapoarte/') . '/';
		$fisier  = 'xlsfile:/' . $locatie . 'Raport_' . $_GET['start'] . '_' . $_GET['end'] . '.xls'; 
		
		header ("Cache-Control: no-cache, must-revalidate"); 
		header ("Pragma: no-cache"); 
		header ("Content-type: application/x-msexcel"); 
		header ("Content-Disposition: attachment; filename=\"" . basename($fisier) . "\"" ); 
	
		$fp = fopen($fisier, "wb"); 
		if (!is_resource($fp)) 
			die("Cannot open $export_file"); 

		$data = $s->getRawData($_GET['start'], $_GET['end']);
		fwrite($fp, serialize($data)); 
		fclose($fp);
		
		readfile(substr($fisier,9));
		
		exit;
	}
	
}