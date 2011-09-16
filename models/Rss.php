<?php
class Model_Rss extends Model_Abstract {
	protected $sources;
	protected $cache;
	function init() {
		$this->sources = array(
			'General'	=> 'http://rss.news.yahoo.com/rss/sports',
			'Tennis' 	=> 'http://sports.yahoo.com/tennis/rss.xml',
			'Olympics'	=> 'http://sports.yahoo.com/olympics/rss.xml',
			'Soccer'	=> 'http://sports.yahoo.com/soccer/rss.xml',
			//'Skiing'	=> 'http://sports.yahoo.com/ski/rss.xml', // buggy feed?
			'Cycling'	=> 'http://sports.yahoo.com/sc/rss.xml',
			'Horse Racing'	=> 'http://sports.yahoo.com/rah/rss.xml',
			'Boxing'	=> 'http://sports.yahoo.com/box/rss.xml',
			'Golf'		=> 'http://sports.yahoo.com/golf/rss.xml',
		);
		$this->cache = Zend_Cache::factory(
			'Core',
			'File',
			array(
				'automatic_serialization' => true,
				'lifetime' => 3600
			),
			array(
				'cache_dir' => APP_PATH.'/../cache'
			)
		);
	}
	
	function pickTwoFeeds() {
		$sN = array();
		foreach($this->sources as $id => $val)
			$sN[] = $id;
		$n = count($sN);
		
		$first = mt_rand(0, $n-1);
		
		do 
			$second = mt_rand(0, $n-1);
		while ($second == $first);
		
		
		return array( $sN[$first], $sN[$second] );
	}
	
	function printFeeds( $feeds = array() ) {
		if (!count($feeds))
			return null;
		echo '<ul>';
		
		foreach($feeds as $title)
			echo '<li>'.$this->feedFormatted($title).'</li>';
		
		echo '</ul>';
	}
	
	function feedFormatted( $channel, $frontPage = 1 ) {
		if (!array_key_exists($channel, $this->sources))
			return null;
		$string = '<h2>'.$channel.'</h2><ul>';
		
		foreach($this->consume($channel) as $item)
			if ($frontPage)
				$string .= '<li><a href="'.$item->link().'">'.$item->title().'</a></li>';
			else
				$string .= '<li>
					<a href="'.$item->link().'"><h3>'.$item->title().'</h3></a> 
						<em>'.new Zend_Date($item->pubDate(),Zend_Date::RSS).'</em><br />
					<p>' . 
						substr(strip_tags($item->description()),0, 255) .
					'... </p>
				</li>';
			
		
		return $string . '</ul>';
	}
	
	private function consume( $channelName ) {
		if ( ($channel = $this->cache->load(md5($channelName))) === false ) {
			$channel = new Zend_Feed_Rss($this->sources[$channelName]);
			$this->cache->save($channel);
		}
		return $channel;
	}
	
	public function refreshCache() {
		$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		foreach($this->sources as $id => $src)
			$this->consume( $id );
	}
	
}