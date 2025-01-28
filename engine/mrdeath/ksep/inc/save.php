<?php 

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

ini_set('memory_limit', '1024M');

require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';

$id = intval($do == "editnews" ? $item_db[0] : $id);
$current_time = time();
parse_str_unlimited($_POST['series'], $seriespages);
$seriespages = $seriespages['seriespages'] ? $seriespages['seriespages'] : [];

if ( (!empty($seriespages) || $_POST['series_removed'] == '1') && $id > 0 ) {
  
  	$newseriesarr = [];
  	
  	if ( count($seriespages) == 1 && $series_options['season']['one_season'] ) $one_season = 1;
  	else $one_season = false;

	foreach ($seriespages as $season_num => $season) {
		if ( $season['season_num'] ) $season_num = trim($season['season_num']);
		else continue;

		if ( empty($season['episodes']) || !$season_num || $season_num < 0 ) continue;
      
      	$newseriesarr[$season_num]['season_num'] = $season_num;

		foreach ($season['episodes'] as $episode_num => $episode) {
			if ( $episode['episode_num'] ) $episode_num = trim($episode['episode_num']);
			else continue;

			if ( !$episode_num || $episode_num < 0 ) continue;

			$date = (!empty($episode['date'])) ? strtotime($episode['date']) : time();
			if ( $date <= 0 || intval(@$episode['date_now']) ) $date = time();

			$approve = (intval(@$episode['approve'])) ? '1' : '0';

			$fields = fieldscompile($episode['fields']);
			$fields = trim($fields);

			$players = '';

			if ( !empty($episode['players']) ) {
				$players = [];

				foreach ($episode['players']['text'] as $i => $text) {
					$text = trim($text);
					$link = trim($episode['players']['link'][$i]);

					if ( $text && $link ) {
						$players[] = [
							'text' => $text,
							'link' => $link
						];
					}
				}

				$players = json_encode($players, JSON_UNESCAPED_UNICODE);
				$players = trim($players);
			}
          	
          	$newseriesarr[$season_num]['episodes'][$episode_num]['episode_num'] = $episode_num;
			$newseriesarr[$season_num]['episodes'][$episode_num]['fields'] = $fields;
			$newseriesarr[$season_num]['episodes'][$episode_num]['players'] = $players;
			$newseriesarr[$season_num]['episodes'][$episode_num]['date'] = $date;
			$newseriesarr[$season_num]['episodes'][$episode_num]['approve'] = $approve;
		}

		$date = (!empty($season['date'])) ? strtotime($season['date']) : time();
		if ( $date <= 0 || intval(@$season['date_now']) ) $date = time();

		$approve = (intval(@$season['approve'])) ? '1' : '0';
        $shorturl = (intval(@$season['shorturl'])) ? '1' : '0';
        if ( $one_season ) $shorturl = $one_season;

		$fields = fieldscompile($season['fields']);
		$fields = trim($fields);
      
      	$newseriesarr[$season_num]['fields'] = $fields;
      	$newseriesarr[$season_num]['date'] = $date;
      	$newseriesarr[$season_num]['approve'] = $approve;
      	$newseriesarr[$season_num]['shorturl'] = $shorturl;
      	
      	if ( !$newseriesarr[$season_num]['episodes'] ) unset($newseriesarr[$season_num]);
	}
	
  	$newseriesarr = json_encode($newseriesarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  	
	ksep_create('episodes_'.$id, $newseriesarr);
}

?>