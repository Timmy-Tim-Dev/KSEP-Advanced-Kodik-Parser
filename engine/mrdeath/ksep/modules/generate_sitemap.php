<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

    function generate_ksep() {
		
		global $db, $config;
  		
  		$ksep_news_ids = [];
  		$ksep_news_list = [];
  		$ksep_files = glob(ENGINE_DIR.'/mrdeath/ksep/episodes_list' . "/*.json");
		foreach ($ksep_files as $ksep_file) {
    		if (is_file($ksep_file)) {
              	$ksep_file_temp = explode('_', basename($ksep_file, ".json"));
              	if ( !$ksep_file_temp[1] ) continue;
                $nwsid = intval($ksep_file_temp[1]);
              	$episodes_list = file_get_contents(ENGINE_DIR.'/mrdeath/ksep/episodes_list/episodes_'.$nwsid.'.json');
                if ( $episodes_list ) $episodes_list = json_decode($episodes_list, true);
                else continue;
              	$ksep_news_ids[] = $nwsid;
              	$ksep_news_list[$nwsid] = $episodes_list;
    		}
		}
  		$ksep_news_ids = array_unique($ksep_news_ids);
		
		$thisdate = date( "Y-m-d H:i:s", time() );
		if( $config['no_date'] AND !$config['news_future'] ) $where_date = " AND date < '" . $thisdate . "'";
		else $where_date = "";
  		
  		$episodes_arr = $seasons_arr = [];
  		$this->db_result = $db->query( "SELECT p.id, p.title, p.date, p.alt_name, p.category, e.access, e.editdate, e.disable_index, e.need_pass, e.related_ids FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras e ON (p.id=e.news_id) WHERE p.id IN ('" . implode ( "','", $ksep_news_ids ) . "') AND approve=1 " . $where_date . " ORDER BY date DESC" );
  		$i = 0;
  		while ( $row = $db->get_row( $this->db_result ) ) {
          	if ( !isset($ksep_news_list[$row['id']]) ) continue;
          	$row['date'] = strtotime($row['date']);
			$row['category'] = intval( $row['category'] );
			if ( $row['disable_index'] ) continue;
			if ( $row['need_pass'] ) continue;
			if (strpos( $row['access'], '5:3' ) !== false) continue;
          	
          	if( $row['category'] and $config['seo_type'] == 2 ) {
				$cats_url = get_url( $row['category'] );
				if($cats_url) {
					$loc = $cats_url . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
				}
                else $loc = $row['id'] . "-" . $row['alt_name'] . ".html";
			}
            else {
				$loc = $row['id'] . "-" . $row['alt_name'] . ".html";
			}
              
            if ( $row['editdate'] AND $row['editdate'] > $row['date'] ){
				$row['date'] =  $row['editdate'];
			}
          
          	foreach ( $ksep_news_list[$row['id']] as $season_num => $season ) {
              	if ( $season['approve'] == 0 ) continue;
                if ( $season['shorturl'] == 1 ) continue;
                if ( !$season['episodes'] ) continue;
                if ( $series_options['future'] == 1 && $season['date'] > $_TIME ) continue;
              
              	if ( !$season['date'] ) $season['date'] = $row['date'];
              
              	$season_link = str_replace('.html', '/season-'.$season_num.'.html', $loc);
              	
              	$seasons_arr[] = [
                	'link' => $season_link,
                    'date' => $season['date']
                ];
              	foreach ( $season['episodes'] as $episode_num => $episode ) {
                  	if ( $episode['approve'] == 0 ) continue;
                  	if ( $series_options['require_players'] == 1 && !$episode['players'] ) continue;
                	if ( $series_options['future'] == 1 && $episode['date'] > $_TIME ) continue;
                  
                  	if ( !$episode['date'] ) $episode['date'] = $row['date'];
                  
                  	if ( $season['shorturl'] == 1 ) $episode_link = str_replace('.html', '/episode-'.$episode_num.'.html', $loc);
                    else $episode_link = str_replace('.html', '/season-'.$season_num.'/episode-'.$episode_num.'.html', $loc);
                  
                  	$episodes_arr[] = [
                      	'link' => $episode_link,
                      	'date' => $episode['date']
                    ];
                }
            }
        }
		
  		$seasons_count = count($seasons_arr);
  		if ( !$this->limit ) $this->limit = $seasons_count;
  		if ( $this->limit > $this->news_per_file ) {
			$pages_count = @ceil( $seasons_count / $this->news_per_file );
			$n = 0;
			for ($i =0; $i < $pages_count; $i++) {
				$n = $n+1;
				$this->get_ksep_seasons($n, $seasons_arr);
			}
		} else {
			$this->get_ksep_seasons(false, $seasons_arr);
		}
  		
  		$episodes_count = count($episodes_arr);
  		$this->limit = $episodes_count;
  		if ( $this->limit > $this->news_per_file ) {
			$pages_count2 = @ceil( $episodes_count / $this->news_per_file );
			$n = 0;
			for ($i =0; $i < $pages_count2; $i++) {
				$n = $n+1;
              	$this->get_ksep_episodes($n, $episodes_arr);
			}
		} else {
			$this->get_ksep_episodes(false, $episodes_arr);
		}
	}