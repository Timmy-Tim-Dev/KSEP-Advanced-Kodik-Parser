<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

if ( file_exists(ENGINE_DIR . '/mrdeath/aaparser/data/config.php') ) {
    if ( $shikiid ) $kodik_material_api = request($kodik_api_domain."search?token=".$kodik_apikey."&shikimori_id=".$shikiid."&with_episodes_data=true&with_material_data=true");
    elseif ( $mdlid ) $kodik_material_api = request($kodik_api_domain."search?token=".$kodik_apikey."&mdl_id=".$mdlid."&with_episodes_data=true&with_material_data=true");
    
    if ( $kodik_material_api['results'] ) {
        $ksep_arr = [];
        $its_ongoing = false;
        foreach ( $kodik_material_api['results'] as $material_result ) {
            if ( $material_result['material_data']['all_status'] == 'ongoing' ) $its_ongoing = true;
			if (!isset($series_options['parse_special']) && 
			(isset($material_result['seasons']['0']) || 
			(isset($material_result['seasons']['0']) && $material_result['translation']['id'] == 1291))) continue;
            foreach ( $material_result['seasons'] as $snum => $material_season ) {
                foreach ( $material_season['episodes'] as $ep_num => $material_episode ) {
                    $ksep_arr[$snum][$ep_num]['players'][str_replace('"', "'", $material_result['translation']['title'])] = $material_episode['link'].'?season='.$snum.'&episode='.$ep_num.'&only_translations='.$material_result['translation']['id'].'&hide_selectors=true';
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr1'] ) && isset($material_episode['screenshots'][0]) ) $ksep_arr[$snum][$ep_num]['kadr1'] = $material_episode['screenshots'][0];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr2'] ) && isset($material_episode['screenshots'][1]) ) $ksep_arr[$snum][$ep_num]['kadr2'] = $material_episode['screenshots'][1];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr3'] ) && isset($material_episode['screenshots'][2]) ) $ksep_arr[$snum][$ep_num]['kadr3'] = $material_episode['screenshots'][2];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr4'] ) && isset($material_episode['screenshots'][3]) ) $ksep_arr[$snum][$ep_num]['kadr4'] = $material_episode['screenshots'][3];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr5'] ) && isset($material_episode['screenshots'][4]) ) $ksep_arr[$snum][$ep_num]['kadr5'] = $material_episode['screenshots'][4];
                }
            }
        }
        
        $need_ksep_cron = false;
        if ( isset( $series_options['aap']['kadr1_img'] ) && isset( $series_options['fields']['episode'][$series_options['aap']['kadr1_img']] ) && $series_options['fields']['episode'][$series_options['aap']['kadr1_img']][3] == 'image' ) $need_ksep_cron = true;
        if ( isset( $series_options['aap']['kadr2_img'] ) && isset( $series_options['fields']['episode'][$series_options['aap']['kadr2_img']] ) && $series_options['fields']['episode'][$series_options['aap']['kadr2_img']][3] == 'image' ) $need_ksep_cron = true;
        if ( isset( $series_options['aap']['kadr3_img'] ) && isset( $series_options['fields']['episode'][$series_options['aap']['kadr3_img']] ) && $series_options['fields']['episode'][$series_options['aap']['kadr3_img']][3] == 'image' ) $need_ksep_cron = true;
        if ( isset( $series_options['aap']['kadr4_img'] ) && isset( $series_options['fields']['episode'][$series_options['aap']['kadr4_img']] ) && $series_options['fields']['episode'][$series_options['aap']['kadr4_img']][3] == 'image' ) $need_ksep_cron = true;
        if ( isset( $series_options['aap']['kadr5_img'] ) && isset( $series_options['fields']['episode'][$series_options['aap']['kadr5_img']] ) && $series_options['fields']['episode'][$series_options['aap']['kadr5_img']][3] == 'image' ) $need_ksep_cron = true;
        
        if ( $need_ksep_cron === false ) {
        
            $episodes_cache = ksep_read('episodes_'.$rowid);
            if ( $episodes_cache !== false ) {
            $episodes_cache = json_decode($episodes_cache, true);
                if ( !is_array($episodes_cache) ) $episodes_cache = [];
            }
            else $episodes_cache = [];
        
            if ( $ksep_arr ) {
            
                $material_title = totranslit_it($kodik_material_api['results'][0]['title_orig'], true, false);
                
                $max_season = $max_episode = 0;
            
                if ( count($ksep_arr) > 1 ) $ksep_seasons_show = 0;
                elseif ( count($ksep_arr) == 1 && $series_options['season']['one_season'] == 1 ) $ksep_seasons_show = 1;
                else $ksep_seasons_show = 0;
                foreach ( $ksep_arr as $snum => $ksep_eps ) {
                    if ( !isset($episodes_cache[$snum]['season_num']) ) $episodes_cache[$snum]['season_num'] = $snum;
                    if ( $snum > $max_season ) $max_season = $snum;
                    foreach ( $ksep_eps as $epnum => $ksep_players ) {
                        if ( isset($episodes_cache[$snum]['episodes'][$epnum]['fields']) && $episodes_cache[$snum]['episodes'][$epnum]['fields'] ) $episode_fields = xfieldsdataload($episodes_cache[$snum]['episodes'][$epnum]['fields']);
                        else $episode_fields = [];
                        if ( !isset($episodes_cache[$snum]['episodes'][$epnum]['episode_num']) ) $episodes_cache[$snum]['episodes'][$epnum]['episode_num'] = $epnum;
                        $ksep_players_arr = $ksep_translations_arr = [];
                        foreach ( $ksep_players['players'] as $trname => $trlink ) {
                            $ksep_translations_arr[] = str_replace('"', "'", $trname);
                            $ksep_players_arr[] = ['text' => $trname, 'link' => $trlink];
                        }
                    
                        if ( isset($series_options['aap']['kadr1_img']) && isset($ksep_players['kadr1']) && !isset($episode_fields[$series_options['aap']['kadr1_img']]) ) {
                            if ( $series_options['fields']['episode'][$series_options['aap']['kadr1_img']][3] == 'image' ) {
                            $screen_1_file = $screen_named.'_season_'.$snum.'_episode_'.$epnum.'_kadr_1';
                                $screen_1 = ksepPoster($ksep_players['kadr1'], $screen_1_file, $series_options['aap']['kadr1_img'], $rowid);
                                if ( isset($screen_1) && is_array($screen_1) && isset($screen_1['xfvalue']) && $screen_1['xfvalue'] ) {
                                    $episode_fields[$series_options['aap']['kadr1_img']] = $screen_1['xfvalue'];
                                }
                                else $episode_fields[$series_options['aap']['kadr1_img']] = '';
                            }
                            else $episode_fields[$series_options['aap']['kadr1_img']] = $ksep_players['kadr1'];
                        }
                    
                        if ( isset($series_options['aap']['kadr2_img']) && isset($ksep_players['kadr2']) && !isset($episode_fields[$series_options['aap']['kadr2_img']]) ) {
                            if ( $series_options['fields']['episode'][$series_options['aap']['kadr2_img']][3] == 'image' ) {
                                $screen_2_file = $screen_named.'_season_'.$snum.'_episode_'.$epnum.'_kadr_2';
                                $screen_2 = ksepPoster($ksep_players['kadr2'], $screen_2_file, $series_options['aap']['kadr2_img'], $rowid);
                                if ( isset($screen_2) && is_array($screen_2) && isset($screen_2['xfvalue']) &&         $screen_2['xfvalue'] ) {
                                    $episode_fields[$series_options['aap']['kadr2_img']] = $screen_2['xfvalue'];
                                }
                                else $episode_fields[$series_options['aap']['kadr2_img']] = '';
                            }
                            else $episode_fields[$series_options['aap']['kadr2_img']] = $ksep_players['kadr2'];
                        }
                    
                        if ( isset($series_options['aap']['kadr3_img']) && isset($ksep_players['kadr3']) && !isset($episode_fields[$series_options['aap']['kadr3_img']]) ) {
                            if ( $series_options['fields']['episode'][$series_options['aap']['kadr3_img']][3] == 'image' ) {
                                $screen_3_file = $screen_named.'_season_'.$snum.'_episode_'.$epnum.'_kadr_3';
                                $screen_3 = ksepPoster($ksep_players['kadr3'], $screen_3_file, $series_options['aap']['kadr3_img'], $rowid);
                                if ( isset($screen_3) && is_array($screen_3) && isset($screen_3['xfvalue']) && $screen_3['xfvalue'] ) {
                                    $episode_fields[$series_options['aap']['kadr3_img']] = $screen_3['xfvalue'];
                                }
                                else $episode_fields[$series_options['aap']['kadr3_img']] = '';
                            }
                            else $episode_fields[$series_options['aap']['kadr3_img']] = $ksep_players['kadr3'];
                        }
                    
                        if ( isset($series_options['aap']['kadr4_img']) && isset($ksep_players['kadr4']) && !isset($episode_fields[$series_options['aap']['kadr4_img']]) ) {
                            if ( $series_options['fields']['episode'][$series_options['aap']['kadr4_img']][3] == 'image' ) {
                                $screen_4_file = $screen_named.'_season_'.$snum.'_episode_'.$epnum.'_kadr_4';
                                $screen_4 = ksepPoster($ksep_players['kadr4'], $screen_4_file, $series_options['aap']['kadr4_img'], $rowid);
                                if ( isset($screen_4) && is_array($screen_4) && isset($screen_4['xfvalue']) && $screen_4['xfvalue'] ) {
                                    $episode_fields[$series_options['aap']['kadr4_img']] = $screen_4['xfvalue'];
                                }
                                else $episode_fields[$series_options['aap']['kadr4_img']] = '';
                            }
                            else $episode_fields[$series_options['aap']['kadr4_img']] = $ksep_players['kadr4'];
                        }
                    
                        if ( isset($series_options['aap']['kadr5_img']) && isset($ksep_players['kadr5']) && !isset($episode_fields[$series_options['aap']['kadr5_img']]) ) {
                            if ( $series_options['fields']['episode'][$series_options['aap']['kadr5_img']][3] == 'image' ) {
                                $screen_5_file = $screen_named.'_season_'.$snum.'_episode_'.$epnum.'_kadr_5';
                                $screen_5 = ksepPoster($ksep_players['kadr5'], $screen_5_file, $series_options['aap']['kadr5_img'], $rowid);
                                if ( isset($screen_5) && is_array($screen_5) && isset($screen_5['xfvalue']) &&         $screen_5['xfvalue'] ) {
                                    $episode_fields[$series_options['aap']['kadr5_img']] = $screen_5['xfvalue'];
                                }
                                else $episode_fields[$series_options['aap']['kadr5_img']] = '';
                            }
                            else $episode_fields[$series_options['aap']['kadr5_img']] = $ksep_players['kadr5'];
                        }
                    
                        if ( isset($series_options['aap']['translations']) && $ksep_translations_arr ) {
                            $ksep_translations_arr = array_unique($ksep_translations_arr);
                            $episode_fields[$series_options['aap']['translations']] = implode(', ', $ksep_translations_arr);
                        }
                        $episodes_cache[$snum]['episodes'][$epnum]['fields'] = xfieldsdatasaved($episode_fields);
                        $episodes_cache[$snum]['episodes'][$epnum]['players'] = json_encode($ksep_players_arr, JSON_UNESCAPED_UNICODE);
                        if ( !isset($episodes_cache[$snum]['episodes'][$epnum]['date']) ) $episodes_cache[$snum]['episodes'][$epnum]['date'] = $_TIME;
                        $episodes_cache[$snum]['episodes'][$epnum]['approve'] = 1;
                        if ( $epnum > $max_episode ) $max_episode = $epnum;
                    }
                    ksort($episodes_cache[$snum]);
                    if ( !isset($episodes_cache[$snum]['fields']) ) $episodes_cache[$snum]['fields'] = '';
                    if ( !isset($episodes_cache[$snum]['date']) ) $episodes_cache[$snum]['date'] = $_TIME;
                    $episodes_cache[$snum]['approve'] = 1;
                    $episodes_cache[$snum]['shorturl'] = $ksep_seasons_show;
                }
            
                ksort($episodes_cache);
                
                if ( $its_ongoing === true && isset($series_options['aap']['plus_episode']) && $series_options['aap']['plus_episode'] ) {
                    $plus_episode = intval($series_options['aap']['plus_episode']);
                    if ( $plus_episode > 0 ) {
                        for ($i = 1; $i <= $plus_episode; $i++) {
                            $plus_episode_num = $max_episode+$i;
                            $episodes_cache[$max_season]['episodes'][$plus_episode_num]['episode_num'] = $plus_episode_num;
                            $episodes_cache[$max_season]['episodes'][$plus_episode_num]['date'] = $_TIME;
                            $episodes_cache[$max_season]['episodes'][$plus_episode_num]['approve'] = 1;
                        }
                        ksort($episodes_cache[$max_season]['episodes']);
                    }
                }
            
                $episodes_cache = json_encode($episodes_cache, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  	
	            ksep_create('episodes_'.$rowid, $episodes_cache);
            
            }
        
        }
        elseif ( $ksep_arr ) {
            
            $material_title = totranslit_it($kodik_material_api['results'][0]['title_orig'], true, false);
            $material_title = $db->safesql($material_title);
            
            $cron_tasks_arr = [];
            $sql_check = "SELECT season, episode FROM " . PREFIX . "_ksep_cron WHERE news_id='".$rowid."'";
            $sql_check_result = $db->query( $sql_check );
	        while ( $cron_check = $db->get_row( $sql_check_result ) ) {
		        $cron_tasks_arr[$cron_check['season']][$cron_check['episode']] = $rowid;
	        }
  	        $db->free( $sql_check_result );
  	        
  	        $ksep_count_seasons = count($ksep_arr);
  	        ksort($ksep_arr);
            
            foreach ( $ksep_arr as $snum => $ksep_eps ) {
                ksort($ksep_arr[$snum]);
                foreach ( $ksep_eps as $epnum => $ksep_players ) {
                    if ( isset($cron_tasks_arr[$snum][$epnum]) && $cron_tasks_arr[$snum][$epnum] ) continue;
                    else {
                        $ksep_cron_data = json_encode($ksep_players, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        $ksep_cron_data = $db->safesql($ksep_cron_data);
                        $db->query( "INSERT INTO " . PREFIX . "_ksep_cron (news_id, season, episode, data, season_count, title) values ('{$rowid}', '{$snum}', '{$epnum}', '{$ksep_cron_data}', '{$ksep_count_seasons}', '{$material_title}')" );
                    }
                }
            }
            
            if ( isset($series_options['aap']['plus_episode']) && $series_options['aap']['plus_episode'] ) {
                $plus_episode = intval($series_options['aap']['plus_episode']);
                if ( $plus_episode > 0 ) {
                    for ($i = 1; $i <= $plus_episode; $i++) {
                        $plus_episode_num = $max_episode+$i;
                        $db->query( "INSERT INTO " . PREFIX . "_ksep_cron (news_id, season, episode, data, season_count, title) values ('{$rowid}', '{$max_season}', '{$plus_episode_num}', '', '{$ksep_count_seasons}', '{$material_title}')" );
                    }
                }
            }
            
        }
    }
}
