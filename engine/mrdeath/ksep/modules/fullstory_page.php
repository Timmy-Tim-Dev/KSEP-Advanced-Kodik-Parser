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
            $episodes_links = preg_replace( "'\[episode-active\](.*?)\[/episode-active\]'si", "", $episodes_links );
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