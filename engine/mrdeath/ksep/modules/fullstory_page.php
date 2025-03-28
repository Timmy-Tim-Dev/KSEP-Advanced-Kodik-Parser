<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

$episodes_list = ksep_read('episodes_'.$row['id']);
if ( $episodes_list !== false ) {
    $episodes_list = json_decode($episodes_list, true);
    $first_season_key = array_key_first($episodes_list);
    if ( is_array($episodes_list) && count($episodes_list) == 1 && ( $episodes_list[$first_season_key]['shorturl'] == 1 || $series_options['season']['one_season'] == 1 || $series_options['only_one'] == 1) ) {
        $episodes_links = '';
        $episodes_links_tpl = file_get_contents(TEMPLATE_DIR.'/kodik_episodes/episodes_links.tpl');
        if ( $series_options['main']['sort_episodes'] == 1 ) krsort($episodes_list[$first_season_key]['episodes']);
        else ksort($episodes_list[$first_season_key]['episodes']);
        foreach ( $episodes_list[$first_season_key]['episodes'] as $episode_num => $episode ) {
            if ( $episode['approve'] == 0 ) continue;
            if ( $series_options['require_players'] == 1 && !$episode['players'] ) continue;
            if ( $series_options['future'] == 1 && $episode['date'] > $_TIME ) continue;
			
            $episodes_links .= str_replace(['{episode-link}', '{episode-num}', '{season-num}'], [ksep_generate_links($full_link, false, $episode_num), $episode_num, $first_season_key], $episodes_links_tpl);
			if ($series_options['cookie_saves'] == 1) $takedcookies = $_COOKIE["kodik_newsid_".$row['id']."_episode_".$episode_num];
			else $takedcookies = null;
			if ($takedcookies !== null) {
				$sw_cookies = (json_decode($takedcookies, true));
				$sw_time = isset($sw_cookies['time']) && $sw_cookies['time'] > 0 ? floor($sw_cookies['time'] / 60) : 0;
				$sw_duration = isset($sw_cookies['duration']) && $sw_cookies['duration'] > 0 ? floor($sw_cookies['duration'] / 60) . "мин." : 0;
				$sw_voice = isset($sw_cookies['voice']) && $sw_cookies['voice'] !== '' ? $sw_cookies['voice'] : '';
				if (isset($sw_cookies['time']) && isset($sw_cookies['duration']) && $sw_cookies['time'] > 0 && $sw_cookies['duration'] > 0) {
					$progress = round(($sw_cookies['time'] / (int)$sw_cookies['duration']) * 100);
				} else $progress = 0;
				// echo "<br>Episode->(".$episode_num."), Time->(".$sw_time."), Duration->(".$sw_duration."), Progress->(".$progress.")", Voice->(".$sw_voice.")"; // Test echo
			}
			if (!empty($sw_voice)) {
				$episodes_links = str_replace( '{episode-currentvoice}', $sw_voice, $episodes_links );
				$episodes_links = str_replace( '[episode-currentvoice]', '', $episodes_links );
				$episodes_links = str_replace( '[/episode-currentvoice]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[not-episode-currentvoice\\](.*?)\\[/not-episode-currentvoice\\]'si", "", $episodes_links );
			} else {
				$episodes_links = str_replace( '{episode-currentvoice}', '', $episodes_links );
				$episodes_links = str_replace( '[not-episode-currentvoice]', '', $episodes_links );
				$episodes_links = str_replace( '[/not-episode-currentvoice]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[episode-currentvoice\\](.*?)\\[/episode-currentvoice\\]'si", "", $episodes_links );
			}
			if ($sw_time > 0) {
				$episodes_links = str_replace( '{episode-currenttime}', $sw_time, $episodes_links );
				$episodes_links = str_replace( '[episode-currenttime]', '', $episodes_links );
				$episodes_links = str_replace( '[/episode-currenttime]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[not-episode-currenttime\\](.*?)\\[/not-episode-currenttime\\]'si", "", $episodes_links );
			} else {
				$episodes_links = str_replace( '{episode-currenttime}', '', $episodes_links );
				$episodes_links = str_replace( '[not-episode-currenttime]', '', $episodes_links );
				$episodes_links = str_replace( '[/not-episode-currenttime]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[episode-currenttime\\](.*?)\\[/episode-currenttime\\]'si", "", $episodes_links );
			}
			if ($sw_duration > 0) {
				$episodes_links = str_replace( '{episode-duration}', $sw_duration, $episodes_links );
				$episodes_links = str_replace( '[episode-duration]', '', $episodes_links );
				$episodes_links = str_replace( '[/episode-duration]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[not-episode-duration\\](.*?)\\[/not-episode-duration\\]'si", "", $episodes_links );
			} else {
				$episodes_links = str_replace( '{episode-duration}', '', $episodes_links );
				$episodes_links = str_replace( '[not-episode-duration]', '', $episodes_links );
				$episodes_links = str_replace( '[/not-episode-duration]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[episode-duration\\](.*?)\\[/episode-duration\\]'si", "", $episodes_links );
			}
			if ($progress > 0) {
				$episodes_links = str_replace( '{episode-progress}', $progress, $episodes_links );
				$episodes_links = str_replace( '[episode-progress]', '', $episodes_links );
				$episodes_links = str_replace( '[/episode-progress]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[not-episode-progress\\](.*?)\\[/not-episode-progress\\]'si", "", $episodes_links );
			} else {
				$episodes_links = str_replace( '{episode-progress}', '', $episodes_links );
				$episodes_links = str_replace( '[not-episode-progress]', '', $episodes_links );
				$episodes_links = str_replace( '[/not-episode-progress]', '', $episodes_links );
				$episodes_links = preg_replace( "'\\[episode-progress\\](.*?)\\[/episode-progress\\]'si", "", $episodes_links );
			}
			
            $episodes_links = preg_replace( "'\[episode-active\](.*?)\[/episode-active\]'si", "", $episodes_links );
			unset($takedcookies, $sw_cookies, $sw_time, $sw_duration, $sw_voice, $progress);
		}
		
        if ( !empty($episodes_links) ) {
            $tpl->set( '{episodes-links}', $episodes_links );
            $tpl->set( '[episodes-links]', '' );
            $tpl->set( '[/episodes-links]', '' );
			$tpl->set_block( "'\\[not-episodes-links\\](.*?)\\[/not-episodes-links\\]'si", "" );
        }
        else {
            $tpl->set_block( "'\\[episodes-links\\](.*?)\\[/episodes-links\\]'si", "" );
            $tpl->set( '{episodes-links}', '' );
			$tpl->set( '[not-episodes-links]', '' );
			$tpl->set( '[/not-episodes-links]', '' );
        }
        $tpl->set_block( "'\\[seasons-links\\](.*?)\\[/seasons-links\\]'si", "" );
        $tpl->set( '{seasons-links}', '' );
		$tpl->set( '[not-seasons-links]', '' );
		$tpl->set( '[/not-seasons-links]', '' );
    }
    elseif ( is_array($episodes_list) ) {
        $seasons_links = '';
        $seasons_links_tpl = file_get_contents(TEMPLATE_DIR.'/kodik_episodes/seasons_links.tpl');
        if ( $series_options['main']['sort_seasons'] == 1 ) krsort($episodes_list);
        else ksort($episodes_list);
        foreach ( $episodes_list as $season_num => $season ) {
            if ( $season['approve'] == 0 ) continue;
            if ( $season['shorturl'] == 1 ) continue;
            if ( $series_options['future'] == 1 && $season['date'] > $_TIME ) continue;
            $seasons_links .= str_replace(['{season-link}', '{season-num}'], [ksep_generate_links($full_link, $season_num, false), $season_num], $seasons_links_tpl);
            $seasons_links = preg_replace( "'\[season-active\](.*?)\[/season-active\]'si", "", $seasons_links );
        }
        if ( !empty($seasons_links) ) {
            $tpl->set( '{seasons-links}', $seasons_links );
            $tpl->set( '[seasons-links]', '' );
            $tpl->set( '[/seasons-links]', '' );
			$tpl->set_block( "'\\[not-seasons-links\\](.*?)\\[/not-seasons-links\\]'si", "" );
        }
        else {
            $tpl->set_block( "'\\[seasons-links\\](.*?)\\[/seasons-links\\]'si", "" );
            $tpl->set( '{seasons-links}', '' );
			$tpl->set( '[not-seasons-links]', '' );
			$tpl->set( '[/not-seasons-links]', '' );
        }
        $tpl->set_block( "'\\[episodes-links\\](.*?)\\[/episodes-links\\]'si", "" );
        $tpl->set( '{episodes-links}', '' );
		$tpl->set( '[not-episodes-links]', '' );
		$tpl->set( '[/not-episodes-links]', '' );
    }
} else {
    $tpl->set_block( "'\\[seasons-links\\](.*?)\\[/seasons-links\\]'si", "" );
    $tpl->set( '{seasons-links}', '' );
    $tpl->set_block( "'\\[episodes-links\\](.*?)\\[/episodes-links\\]'si", "" );
    $tpl->set( '{episodes-links}', '' );
	$tpl->set( '[not-episodes-links]', '' );
	$tpl->set( '[/not-episodes-links]', '' );
	$tpl->set( '[not-seasons-links]', '' );
	$tpl->set( '[/not-seasons-links]', '' );
}