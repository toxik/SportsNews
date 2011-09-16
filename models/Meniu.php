<?php
class Model_Meniu extends Model_Abstract {
	protected $menus;
	
	function init() {
		// populate the menus
		$this->menus = array(
			'about' => array (
				'/meciuri'				=> 'Meciuri', 
				'/sporturi'				=> 'Sporturi', 
				'/echipe'				=> 'Echipe', 
				'/statice/contact'		=> 'Contact'
			),
			
			'admin' => array(
				'/meciuri'				=> 'Meciuri', 
				'/sporturi'				=> 'Sporturi', 
				'/echipe'				=> 'Echipe',  
				'/statistici'			=> 'Statistici',
				'/utilizatori'			=> 'Utilizatori'
			)
		);
	}
	
	function printMenu( $whichType = 'auto' ) {
		if ($whichType == 'about')
			echo $this->traverseMenu($this->menus['about']);
		if ($whichType == 'auto') {
			$auth = new Model_Auth;
			$type = $auth->getUserDetails();
			$type = $type->type;
			if ($type != 'A')
				echo $this->traverseMenu($this->menus['about']);
			else 
				echo $this->traverseMenu($this->menus['admin']);
		}
	}
	
	private function traverseMenu($menu, $title = '') {
		$currentMenu = ($title ? '<a>'.$title.'</a>' :  '').'<ul>';
		if ($menu)
		foreach($menu as $url => $title)
			$currentMenu .= '<li'.$this->decorate($menu, $title, $url).'>'.( 
								is_array($title) ? 	$this->traverseMenu($title, $url) : 
									'<a href="'.$url.'" title="'.$title.'">'.$title.'</a>' )
							. '</li>';
		return $currentMenu . '</ul>';
	}
	
	private function decorate( $menu, $element, $url ) {
		$dec = ' class="';
		
		$currentPath = '/' . MODULE;
		
		if (substr( $url, 0, strlen($currentPath)) == $currentPath)
			$dec .= 'current_page_item';
		
		return strlen($dec) == 8 ? '' : $dec.'"';
	}
}