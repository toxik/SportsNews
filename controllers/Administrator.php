<?php
class Administrator extends Controller {
	private $dataset_names = 'kinky scalado Cart-wright swypes gluhwein Jape Hanniel garrison house trophy wife hypnopompic equal protection chicken casserole outrightly Jogging Pelecanoididae tableware systolic murmur sylphlike hydrocyanic acid 48CO hematic draw play Religion, Wars of chimere MY56 stir-fry melamine trammel out of place suborder Blattaria down-at-the-heels lead nation Inducting nares Rantipole Tiddle comedically grass pea charge in Unstableness carriole scarped agma one-two punch Emanuel Svedberg Manumotive Alembroth Ship-building Epicene Storying Francis Richard Stockton extend oneself Sail loft bowl or glass boulle monomolecular Stefansson bed jacket cross-dress vena gastrica-dextra Prescind chauffeur Fistuca Ore-weed brachycranial Finland 8M8 Sarda chiliensis C6H5C2H2C2H2CO2H 8PA4 pidginize Shrugging Scalable Sampson William Thomas Beshone ABEL Curstness Kedger Qqon Salinas de Gortari turn again';
	private $dataset_domains = '.net .co.uk .com .ro .org .ca .co .me .cc';
	private $dataset_endings = 'Acct Official User Account Online';

	function init() {
		$_SESSION['user_type'] = 'administrator';
		$this->p['cssf'][] = 'admin';
	}
	
	function index() {
		$events = array(
			'<b>http://</b>mamagila<b>%s</b><b>.com</b>',
			'Attempt of <b>XSS</b> from <b>%s</b>, in <b>%s</b>',
			'Attempt of <b>SQL Injection</b> from <b>%s</b>, in <b>%s</b>'			
		);
		$places = array(
			'Administration console', 'Login system', 'Register system', 'Newsgroups Administration',
			'Widget generation', 'Tracking system'
		);
		for($i = 0; $i < 20; $i++) {
			$ts = time() - $i * mt_rand(1003360, 1253360);
			$ip = mt_rand(1,255) . '.' . mt_rand(0,255) . '.' . mt_rand(0,255) . '.' . mt_rand(1,255);
			$place = $places[mt_rand(0, count($places)-1)];
			$this->p['events'][$ts] = array(
				'date' 		=> date('c', $ts),
				'message'	=> sprintf( $events[mt_rand(0,count($events)-1)], $ip, $place ),
				'measure'	=> sprintf( '<b>%s</b> banned for <b>%s</b> hours', $ip, mt_rand(2, 9) )
			);
		}
		krsort($this->p['events']);
	}
	
	function users() {
		$this->p['users'] = array();
		$usertypes = array('Admin', 'User', 'Furnizor');
		$names = explode(' ', strtolower($this->dataset_names));
		$domains = explode(' ', $this->dataset_domains);
		$accountTerminals = explode(' ', $this->dataset_endings);
		while(count($this->p['users']) < 20) {
			$name = $names[ mt_rand( 0, count($names)-1 ) ];
			$username = $names[ mt_rand( 0, count($names)-1 ) ];
			$username2 = $names[ mt_rand( 0, count($names)-1 ) ];
			$domain = $domains[ mt_rand( 0, count($domains)-1) ];
			$accountTerminal = $accountTerminals[ mt_rand( 0, count($accountTerminals)-1) ];
			
			$this->p['users'][$username] = array(
				'email' 	=> $username.'@'.$name.$domain,
				'user'		=> $username2,
				'type'	=> $usertypes[mt_rand(0, count($usertypes)-1)],
				'active'	=> mt_rand(0,1)
			);
		}
		
	}
	
	function websites() {
		$this->p['websites'] = array();
		$names = explode(' ', strtolower($this->dataset_names));
		$domains = explode(' ', $this->dataset_domains);
		$accountTerminals = explode(' ', $this->dataset_endings);
		for($i = 0; $i < 20; $i++) {
			$name = $names[ mt_rand( 0, count($names)-1 ) ];
			$domain = $domains[ mt_rand( 0, count($domains)-1) ];
			$accountTerminal = $accountTerminals[ mt_rand( 0, count($accountTerminals)-1) ];
			$this->p['websites'][] = array(
				'site' 	=> $name.$domain,
				'user'	=> $name.$accountTerminal,
				'active'	=> mt_rand(0,1)
			);
		}
	}
	
	function newsgroups() {
		$this->p['newsgroups'] = array();
		for($i = 0; $i < 20; $i++)
			$this->p['newsgroups'][] = array(
				'name' 		=> 'Newsgroup #'. mt_rand(1, 30),
				'active'	=> mt_rand(0,1)
			);
	}
}