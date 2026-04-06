<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

	function get_ksep_seasons( $page = false, $seasons_arr = [] ) {
		
		global $db, $config, $user_group, $series_options, $_TIME, $seasons_arr_new;
		
      	if ( $series_options['priority_season'] ) $this->priority = $series_options['priority_season'];
		else $this->priority = $this->news_priority;
		$this->changefreq = $this->news_changefreq;
		$prefix_page = '';
		
		if ( $page ) {
			if( $page != 1 ) $prefix_page = $page;
          	$seasons_arr_new = array_slice($seasons_arr, $page, $page * $this->news_per_file);
		}
      	else $seasons_arr_new = array_slice($seasons_arr, 0, $this->news_per_file);
		
		$file_params = "seasons_pages{$prefix_page}.xml";

		$this->sitemap->links($file_params, function($map) {
			
			global $db, $config, $user_group, $series_options, $_TIME, $seasons_arr_new;
          
          	foreach ( $seasons_arr_new as $num => $season ) {
              	$map->loc($season['link'])->freq($this->changefreq)->lastMod( date('c', $season['date'] ) )->priority( $this->priority );
            }
          	
		});
	}
	function get_ksep_episodes( $page = false, $episodes_arr = [] ) {
		
		global $db, $config, $user_group, $series_options, $_TIME, $episodes_arr_new;
		
      	if ( $series_options['priority_episode'] ) $this->priority = $series_options['priority_episode'];
		else $this->priority = $this->news_priority;
		$this->changefreq = $this->news_changefreq;
		$prefix_page = '';
		
		if ( $page ) {
			if( $page != 1 ) $prefix_page = $page;
          	$episodes_arr_new = array_slice($episodes_arr, $page, $page * $this->news_per_file);
		}
      	else $episodes_arr_new = array_slice($episodes_arr, 0, $this->news_per_file);
		
		$file_params = "episodes_pages{$prefix_page}.xml";

		$this->sitemap->links($file_params, function($map) {
			
			global $db, $config, $user_group, $series_options, $_TIME, $episodes_arr_new;
          
          	foreach ( $episodes_arr_new as $num => $episode ) {
              	$map->loc($episode['link'])->freq($this->changefreq)->lastMod( date('c', $episode['date'] ) )->priority( $this->priority );
            }
          	
		});
	}