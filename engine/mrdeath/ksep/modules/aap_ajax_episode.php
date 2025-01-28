<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

if ( file_exists(ENGINE_DIR . '/mrdeath/aaparser/data/config.php') ) {
        
        $episodes_cache = ksep_read('episodes_'.$rowid);
        if ( $episodes_cache !== false ) {
            $episodes_cache = json_decode($episodes_cache, true);
            if ( !is_array($episodes_cache) ) $episodes_cache = [];
        }
        else $episodes_cache = [];
        
        if ( $ep_data ) {
            
            if ( $sez_count > 1 ) $ksep_seasons_show = 0;
            elseif ( $sez_count == 1 && $series_options['season']['one_season'] == 1 ) $ksep_seasons_show = 1;
            else $ksep_seasons_show = 0;

            if ( !isset($episodes_cache[$sez_num]['season_num']) ) $episodes_cache[$sez_num]['season_num'] = $sez_num;

            if ( isset($episodes_cache[$sez_num]['episodes'][$ep_num]['fields']) && $episodes_cache[$sez_num]['episodes'][$ep_num]['fields'] ) $episode_fields = xfieldsdataload($episodes_cache[$sez_num]['episodes'][$ep_num]['fields']);
            else $episode_fields = [];
            if ( !isset($episodes_cache[$sez_num]['episodes'][$ep_num]['episode_num']) ) $episodes_cache[$sez_num]['episodes'][$ep_num]['episode_num'] = $ep_num;
            $ep_data_arr = $ksep_translations_arr = [];
            foreach ( $ep_data['players'] as $trname => $trlink ) {
                $ksep_translations_arr[] = $trname;
                $ep_data_arr[] = ['text' => $trname, 'link' => $trlink];
            }
                    
            if ( isset($series_options['aap']['kadr1_img']) && isset($ep_data['kadr1']) && !isset($episode_fields[$series_options['aap']['kadr1_img']]) ) {
                if ( $series_options['fields']['episode'][$series_options['aap']['kadr1_img']][3] == 'image' ) {
                    $screen_1_file = $material_title.'_season_'.$sez_num.'_episode_'.$ep_num.'_kadr_1';
                    $screen_1 = ksepPoster($ep_data['kadr1'], $screen_1_file, $series_options['aap']['kadr1_img'], $rowid);
                    if ( isset($screen_1) && is_array($screen_1) && isset($screen_1['xfvalue']) && $screen_1['xfvalue'] ) {
                        $episode_fields[$series_options['aap']['kadr1_img']] = $screen_1['xfvalue'];
                    }
                    else $episode_fields[$series_options['aap']['kadr1_img']] = '';
                }
                else $episode_fields[$series_options['aap']['kadr1_img']] = $ep_data['kadr1'];
            }
                    
            if ( isset($series_options['aap']['kadr2_img']) && isset($ep_data['kadr2']) && !isset($episode_fields[$series_options['aap']['kadr2_img']]) ) {
                if ( $series_options['fields']['episode'][$series_options['aap']['kadr2_img']][3] == 'image' ) {
                    $screen_2_file = $material_title.'_season_'.$sez_num.'_episode_'.$ep_num.'_kadr_2';
                    $screen_2 = ksepPoster($ep_data['kadr2'], $screen_2_file, $series_options['aap']['kadr2_img'], $rowid);
                    if ( isset($screen_2) && is_array($screen_2) && isset($screen_2['xfvalue']) && $screen_2['xfvalue'] ) {
                        $episode_fields[$series_options['aap']['kadr2_img']] = $screen_2['xfvalue'];
                    }
                    else $episode_fields[$series_options['aap']['kadr2_img']] = '';
                }
                else $episode_fields[$series_options['aap']['kadr2_img']] = $ep_data['kadr2'];
            }
                    
            if ( isset($series_options['aap']['kadr3_img']) && isset($ep_data['kadr3']) && !isset($episode_fields[$series_options['aap']['kadr3_img']]) ) {
                if ( $series_options['fields']['episode'][$series_options['aap']['kadr3_img']][3] == 'image' ) {
                    $screen_3_file = $material_title.'_season_'.$sez_num.'_episode_'.$ep_num.'_kadr_3';
                    $screen_3 = ksepPoster($ep_data['kadr3'], $screen_3_file, $series_options['aap']['kadr3_img'], $rowid);
                    if ( isset($screen_3) && is_array($screen_3) && isset($screen_3['xfvalue']) && $screen_3['xfvalue'] ) {
                        $episode_fields[$series_options['aap']['kadr3_img']] = $screen_3['xfvalue'];
                    }
                    else $episode_fields[$series_options['aap']['kadr3_img']] = '';
                }
                else $episode_fields[$series_options['aap']['kadr3_img']] = $ep_data['kadr3'];
            }
                    
            if ( isset($series_options['aap']['kadr4_img']) && isset($ep_data['kadr4']) && !isset($episode_fields[$series_options['aap']['kadr4_img']]) ) {
                if ( $series_options['fields']['episode'][$series_options['aap']['kadr4_img']][3] == 'image' ) {
                    $screen_4_file = $material_title.'_season_'.$sez_num.'_episode_'.$ep_num.'_kadr_4';
                    $screen_4 = ksepPoster($ep_data['kadr4'], $screen_4_file, $series_options['aap']['kadr4_img'], $rowid);
                    if ( isset($screen_4) && is_array($screen_4) && isset($screen_4['xfvalue']) && $screen_4['xfvalue'] ) {
                        $episode_fields[$series_options['aap']['kadr4_img']] = $screen_4['xfvalue'];
                    }
                    else $episode_fields[$series_options['aap']['kadr4_img']] = '';
                }
                else $episode_fields[$series_options['aap']['kadr4_img']] = $ep_data['kadr4'];
            }
                    
            if ( isset($series_options['aap']['kadr5_img']) && isset($ep_data['kadr5']) && !isset($episode_fields[$series_options['aap']['kadr5_img']]) ) {
                if ( $series_options['fields']['episode'][$series_options['aap']['kadr5_img']][3] == 'image' ) {
                    $screen_5_file = $material_title.'_season_'.$sez_num.'_episode_'.$ep_num.'_kadr_5';
                    $screen_5 = ksepPoster($ep_data['kadr5'], $screen_5_file, $series_options['aap']['kadr5_img'], $rowid);
                    if ( isset($screen_5) && is_array($screen_5) && isset($screen_5['xfvalue']) && $screen_5['xfvalue'] ) {
                        $episode_fields[$series_options['aap']['kadr5_img']] = $screen_5['xfvalue'];
                    }
                    else $episode_fields[$series_options['aap']['kadr5_img']] = '';
                }
                else $episode_fields[$series_options['aap']['kadr5_img']] = $ep_data['kadr5'];
            }
                    
            if ( isset($series_options['aap']['translations']) && $ksep_translations_arr ) {
                $ksep_translations_arr = array_unique($ksep_translations_arr);
                $episode_fields[$series_options['aap']['translations']] = implode(', ', $ksep_translations_arr);
            }
            $episodes_cache[$sez_num]['episodes'][$ep_num]['fields'] = xfieldsdatasaved($episode_fields);
            $episodes_cache[$sez_num]['episodes'][$ep_num]['players'] = json_encode($ep_data_arr, JSON_UNESCAPED_UNICODE);
            if ( !isset($episodes_cache[$sez_num]['episodes'][$ep_num]['date']) ) $episodes_cache[$sez_num]['episodes'][$ep_num]['date'] = $_TIME;
            $episodes_cache[$sez_num]['episodes'][$ep_num]['approve'] = 1;

            ksort($episodes_cache[$sez_num]);
            if ( !isset($episodes_cache[$sez_num]['fields']) ) $episodes_cache[$sez_num]['fields'] = '';
            if ( !isset($episodes_cache[$sez_num]['date']) ) $episodes_cache[$sez_num]['date'] = $_TIME;
            $episodes_cache[$sez_num]['approve'] = 1;
            $episodes_cache[$sez_num]['shorturl'] = $ksep_seasons_show;
            
            ksort($episodes_cache);
            
            $episodes_cache = json_encode($episodes_cache, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  	
	        ksep_create('episodes_'.$rowid, $episodes_cache);
	        
	        if ( isset($ksep_material) ) die('NewsID '.$rowid.' '.$sez_num.' сезон '.$ep_num.' серия добавлена');
	        else die(json_encode(array(
	            'status' => 'ok'
	        )));
            
        }
        elseif ( !$ep_data && isset($series_options['aap']['plus_episode']) && $series_options['aap']['plus_episode'] ) {
            if ( $sez_count > 1 ) $ksep_seasons_show = 0;
            elseif ( $sez_count == 1 && $series_options['season']['one_season'] == 1 ) $ksep_seasons_show = 1;
            else $ksep_seasons_show = 0;

            if ( !isset($episodes_cache[$sez_num]['season_num']) ) $episodes_cache[$sez_num]['season_num'] = $sez_num;

            if ( !isset($episodes_cache[$sez_num]['episodes'][$ep_num]['episode_num']) ) $episodes_cache[$sez_num]['episodes'][$ep_num]['episode_num'] = $ep_num;
                    
            if ( !isset($episodes_cache[$sez_num]['episodes'][$ep_num]['date']) ) $episodes_cache[$sez_num]['episodes'][$ep_num]['date'] = $_TIME;
            $episodes_cache[$sez_num]['episodes'][$ep_num]['approve'] = 1;

            ksort($episodes_cache[$sez_num]);
            if ( !isset($episodes_cache[$sez_num]['fields']) ) $episodes_cache[$sez_num]['fields'] = '';
            if ( !isset($episodes_cache[$sez_num]['date']) ) $episodes_cache[$sez_num]['date'] = $_TIME;
            $episodes_cache[$sez_num]['approve'] = 1;
            $episodes_cache[$sez_num]['shorturl'] = $ksep_seasons_show;
            
            ksort($episodes_cache);
            
            $episodes_cache = json_encode($episodes_cache, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  	
	        ksep_create('episodes_'.$rowid, $episodes_cache);
	        
	        if ( isset($ksep_material) ) die('NewsID '.$rowid.' '.$sez_num.' сезон '.$ep_num.' серия добавлена');
	        else die(json_encode(array(
	            'status' => 'ok'
	        )));
        }
        else die(json_encode(array(
	            'error' => 'not ok'
	        )));
        
}
