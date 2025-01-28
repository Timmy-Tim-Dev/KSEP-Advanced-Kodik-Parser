<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

$ksep_founded = false;

$season_xfields = $episode_xfields = [];
if ( isset($series_options['fields']['season']) && $series_options['fields']['season'] ) {
    foreach ( $series_options['fields']['season'] as $season_field ) {
        $season_xfields[$season_field['0']] = $season_field['3'];
    }
}
if ( isset($series_options['fields']['episode']) && $series_options['fields']['episode'] ) {
    foreach ( $series_options['fields']['episode'] as $episode_field ) {
        $episode_xfields[$episode_field['0']] = $episode_field['3'];
    }
}

$episodes_list = ksep_read('episodes_'.$row['id']);
if ( $episodes_list !== false ) {
    $episodes_list = json_decode($episodes_list, true);
    if ( is_array($episodes_list) && $episodes_list ) {
        $current_episode = $_GET['epzd'];
        $current_season = isset($_GET['szn']) ? $_GET['szn'] : array_key_first($episodes_list);
        
        if ( isset($episodes_list[$current_season]['date']) && $episodes_list[$current_season]['date'] > $_TIME && $series_options['future'] == 1 ) $disable_season = true;
        else $disable_season = false;
        
        $disable_episode = false;
        if ( $series_options['require_players'] == 1 && !$episodes_list[$current_season]['episodes'][$current_episode]['players'] ) $disable_episode = true;
        if ( $series_options['future'] == 1 && $episodes_list[$current_season]['episodes'][$current_episode]['date'] > $_TIME ) $disable_episode = true;
        
        if ( isset($episodes_list[$current_season]) && $episodes_list[$current_season]['approve'] == 1 && isset($episodes_list[$current_season]['episodes'][$current_episode]) && $episodes_list[$current_season]['episodes'][$current_episode]['approve'] == 1 && $episodes_list[$current_season]['approve'] == 1 && $disable_season === false && $disable_episode === false ) {
            $ksep_founded = true;
            
            $nearest_eps = findNearestKeys($episodes_list[$current_season]['episodes'], $current_episode);
            $nearest_seasons = findNearestKeys($episodes_list, $current_season);
            
            //Теги для серий
            
            if ( $nearest_eps['prev'] !== null ) {
                
                $disable_prev_ep = false;
                if ( $series_options['require_players'] == 1 && !$episodes_list[$current_season]['episodes'][$nearest_eps['prev']]['players'] ) $disable_prev_ep = true;
                if ( $series_options['future'] == 1 && $episodes_list[$current_season]['episodes'][$nearest_eps['prev']]['date'] > $_TIME ) $disable_prev_ep = true;
                
                if ( $disable_prev_ep === false ) {
                    if ( $episodes_list[$current_season]['shorturl'] == 1 ) $tpl->set( '{episode-prev}', ksep_generate_links($full_link, false, $nearest_eps['prev']) );
                    else $tpl->set( '{episode-prev}', ksep_generate_links($full_link, $current_season, $nearest_eps['prev']) );
                    $tpl->set( '[episode-prev]', '' );
                    $tpl->set( '[/episode-prev]', '' );
                    $tpl->set_block( "'\\[not-episode-prev\\](.*?)\\[/not-episode-prev\\]'si", "" );
                }
                else {
                    $tpl->set( '{episode-prev}', '' );
                    $tpl->set( '[not-episode-prev]', '' );
                    $tpl->set( '[/not-episode-prev]', '' );
                    $tpl->set_block( "'\\[episode-prev\\](.*?)\\[/episode-prev\\]'si", "" );
                }
            }
            else {
                $tpl->set( '{episode-prev}', '' );
                $tpl->set( '[not-episode-prev]', '' );
                $tpl->set( '[/not-episode-prev]', '' );
                $tpl->set_block( "'\\[episode-prev\\](.*?)\\[/episode-prev\\]'si", "" );
            }
            if ( $nearest_eps['next'] !== null ) {
                
                $disable_next_ep = false;
                if ( $series_options['require_players'] == 1 && !$episodes_list[$current_season]['episodes'][$nearest_eps['next']]['players'] ) $disable_next_ep = true;
                if ( $series_options['future'] == 1 && $episodes_list[$current_season]['episodes'][$nearest_eps['next']]['date'] > $_TIME ) $disable_next_ep = true;
                
                if ( $disable_next_ep === false ) {
                    if ( $episodes_list[$current_season]['shorturl'] == 1 ) $tpl->set( '{episode-next}', ksep_generate_links($full_link, false, $nearest_eps['next']) );
                    else $tpl->set( '{episode-next}', ksep_generate_links($full_link, $current_season, $nearest_eps['next']) );
                    $tpl->set( '[episode-next]', '' );
                    $tpl->set( '[/episode-next]', '' );
                    $tpl->set_block( "'\\[not-episode-next\\](.*?)\\[/not-episode-next\\]'si", "" );
                }
                else {
                    $tpl->set( '{episode-next}', '' );
                    $tpl->set( '[not-episode-next]', '' );
                    $tpl->set( '[/not-episode-next]', '' );
                    $tpl->set_block( "'\\[episode-next\\](.*?)\\[/episode-next\\]'si", "" );
                }
            }
            else {
                $tpl->set( '{episode-next}', '' );
                $tpl->set( '[not-episode-next]', '' );
                $tpl->set( '[/not-episode-next]', '' );
                $tpl->set_block( "'\\[episode-next\\](.*?)\\[/episode-next\\]'si", "" );
            }
            
            $compare_date_ep = compare_days_date( $episodes_list[$current_season]['episodes'][$current_episode]['date'] );
		    if( !$compare_date_ep ) $tpl->set( '{episode-date}', $lang['time_heute'] . langdate( ", H:i", $episodes_list[$current_season]['episodes'][$current_episode]['date'] ) );
		    elseif( $compare_date_ep == 1 ) $tpl->set( '{episode-date}', $lang['time_gestern'] . langdate( ", H:i", $episodes_list[$current_season]['episodes'][$current_episode]['date'] ) );
		    else $tpl->set( '{episode-date}', langdate( $config['timestamp_active'], $episodes_list[$current_season]['episodes'][$current_episode]['date'] ) );
		    
		    $news_date = $episodes_list[$current_season]['episodes'][$current_episode]['date'];
		    $tpl->copy_template = preg_replace_callback ( "#\{episode-date=(.+?)\}#i", "formdate", $tpl->copy_template );
		    
		    $tpl->set( '{episode-num}', $current_episode );
		    if ( $episodes_list[$current_season]['shorturl'] == 1 ) $ksep_full_link = ksep_generate_links($full_link, false, $current_episode);
		    else $ksep_full_link = ksep_generate_links($full_link, $current_season, $current_episode);
		    $tpl->set( '{episode-link}',  $ksep_full_link);
		    $canonical = $ksep_full_link;
		    
		    if ( count($episode_xfields) > 0 ) {
		        if ( $episodes_list[$current_season]['episodes'][$current_episode]['fields'] ) $xfields_episode = xfieldsdataload($episodes_list[$current_season]['episodes'][$current_episode]['fields']);
		        else $xfields_episode = [];
		        foreach ( $episode_xfields as $epfieldname => $epfieldkind ) {
		            if ( $epfieldkind == 'text' || $epfieldkind == 'textarea' ) {
		                if ( isset($xfields_episode[$epfieldname]) && $xfields_episode[$epfieldname] ) {
		                    $tpl->set( '{episode-field-'.$epfieldname.'}', $xfields_episode[$epfieldname] );
		                    $tpl->set( '[episode-field-'.$epfieldname.']', '' );
		                    $tpl->set( '[/episode-field-'.$epfieldname.']', '' );
		                    $tpl->set_block( "'\\[not-episode-field-".$epfieldname."\\](.*?)\\[/not-episode-field-".$epfieldname."\\]'si", "" );
		                }
		                else {
		                    $tpl->set( '{episode-field-'.$epfieldname.'}', '' );
		                    $tpl->set( '[not-episode-field-'.$epfieldname.']', '' );
		                    $tpl->set( '[/not-episode-field-'.$epfieldname.']', '' );
		                    $tpl->set_block( "'\\[episode-field-".$epfieldname."\\](.*?)\\[/episode-field-".$epfieldname."\\]'si", "" );
		                }
		            }
		            elseif ( $epfieldkind == 'image' ) {
		                if ( isset($xfields_episode[$epfieldname]) && $xfields_episode[$epfieldname] ) {
		                    
		                    $temp_array = explode('|', $xfields_episode[$epfieldname]);
						
					        if (count($temp_array) == 1 OR count($temp_array) == 5 ){
						        $temp_alt = '';
						        $temp_value = implode('|', $temp_array );
					        } else {
						        $temp_alt = $temp_array[0];
						        $temp_alt = str_replace( "&amp;#44;", "&#44;", $temp_alt );
						        $temp_alt = str_replace( "&amp;#124;", "&#124;", $temp_alt );
						
						        unset($temp_array[0]);
						        $temp_value =  implode('|', $temp_array );
					        }

					        $path_parts = get_uploaded_image_info($temp_value);
		                    
		                    if( $series_options['fields']['episode'][$epfieldname][12] AND $path_parts->thumb ) {
						        $tpl->set( '{episode-field-'.$epfieldname.'_thumb_url}', $path_parts->thumb);
						        $xfields_episode[$epfieldname] = "<a href=\"{$path_parts->url}\" data-highslide=\"single\" target=\"_blank\"><img class=\"xfieldimage {$epfieldname}\" src=\"{$path_parts->thumb}\" alt=\"{$temp_alt}\"></a>";
					        } else {
						        $tpl->set( '{episode-field-'.$epfieldname.'_thumb_url}', $path_parts->url);
						        $xfields_episode[$epfieldname] = "<img class=\"xfieldimage {$epfieldname}\" src=\"{$path_parts->url}\" alt=\"{$temp_alt}\">";
					        }
		                    
		                    if ( isset($schema_images)) $schema_images[] = $path_parts->url;
					
					        $tpl->set( '{episode-field-'.$epfieldname.'_image_url}', $path_parts->url);
					        $tpl->set( '{episode-field-'.$epfieldname.'_image_description}', $temp_alt);
		                    
		                    $tpl->set( '{episode-field-'.$epfieldname.'}', $xfields_episode[$epfieldname] );
		                    $tpl->set( '[episode-field-'.$epfieldname.']', '' );
		                    $tpl->set( '[/episode-field-'.$epfieldname.']', '' );
		                    $tpl->set_block( "'\\[not-episode-field-".$epfieldname."\\](.*?)\\[/not-episode-field-".$epfieldname."\\]'si", "" );
		                }
		                else {
		                    $tpl->set( '{episode-field-'.$epfieldname.'}', '' );
		                    $tpl->set( '[not-episode-field-'.$epfieldname.']', '' );
		                    $tpl->set( '[/not-episode-field-'.$epfieldname.']', '' );
		                    $tpl->set_block( "'\\[episode-field-".$epfieldname."\\](.*?)\\[/episode-field-".$epfieldname."\\]'si", "" );
		                    $tpl->set( '{episode-field-'.$epfieldname.'_image_url}', '' );
		                    $tpl->set( '{episode-field-'.$epfieldname.'_image_description}', '' );
		                    $tpl->set( '{episode-field-'.$epfieldname.'_thumb_url}', '' );
		                }
		            }
		        }
		    }
		    
		    //Формирование плейлиста
		    
		    if ( $episodes_list[$current_season]['episodes'][$current_episode]['players'] ) {
		        $episode_players = json_decode($episodes_list[$current_season]['episodes'][$current_episode]['players'], true);
		        if ( is_array($episode_players) && $episode_players ) {
		            
		            $iframe_link = '';
		            
		            foreach ($episode_players as $player_num => $player_data) {
                        if ( !$iframe_link ) {
                            $active_tr = " active";
                            $iframe_link = $player_data['link'];
                        }
                        else $active_tr = "";
                        if ( stripos($player_data['link'], 'kodik.info') !== false ) $player_data['link'] = $player_data['link'].'?hide_selectors=true';
                        $translators .= '<li id="translator-'.$player_num.'" onclick="kodik_translates_alt();" class="b-translator__item'.$active_tr.'" data-this_link="'.$player_data['link'].'" data-this_translator="'.$player_data['text'].'">'.$player_data['text'].'</li>';
                    }
                    $translators = '<div class="b-translators__block"><div class="b-translators__title">В переводе:</div><ul id="translators-list" class="b-translators__list">'.$translators.'</ul></div>';

                    $ajax_player = '<div id="player" class="b-player" style="text-align: center;">';
        
                    $iframe .= '<div id="ibox"><div id="player_kodik" style="height: 100%; margin: 0 auto; width: 100%;"><iframe src="'.$iframe_link.'" width="724" height="460" frameborder="0" allowfullscreen=""></iframe></div>';
    
                    $ajax_player = $ajax_player . $iframe;

                    $ajax_player .= '</div></div>';
    
                    $ajax_player = $translators . $ajax_player;
    
                    $tpl->set( '{episode-player}', '<div id="kodik_player_ajax">'.$ajax_player.'</div>' );
                    $tpl->set( '[episode-player]', '' );
                    $tpl->set( '[/episode-player]', '' );
                    $tpl->set_block( "'\\[not-episode-player\\](.*?)\\[/not-episode-player\\]'si", "" );
		            
		       }
		       else {
		            $tpl->set( '{episode-player}', '' );
		            $tpl->set( '[not-episode-player]', '' );
                    $tpl->set( '[/not-episode-player]', '' );
                    $tpl->set_block( "'\\[episode-player\\](.*?)\\[/episode-player\\]'si", "" );
		       }
		   }
		   else {
		        $tpl->set( '{episode-player}', '' );
		        $tpl->set( '[not-episode-player]', '' );
                $tpl->set( '[/not-episode-player]', '' );
                $tpl->set_block( "'\\[episode-player\\](.*?)\\[/episode-player\\]'si", "" );
		   }
		    
		    //Теги для сезона
		    
		    if ( $nearest_seasons['prev'] !== null ) {
		        
		        if ( isset($episodes_list[$nearest_seasons['prev']]['date']) && $episodes_list[$nearest_seasons['prev']]['date'] > $_TIME && $series_options['future'] == 1 ) $disable_prev_season = true;
                else $disable_prev_season = false;
		        
		        if ( $disable_prev_season === false ) {
                    $tpl->set( '{season-prev}', ksep_generate_links($full_link, $nearest_seasons['prev'], false) );
                    $tpl->set( '[season-prev]', '' );
                    $tpl->set( '[/season-prev]', '' );
                    $tpl->set_block( "'\\[not-season-prev\\](.*?)\\[/not-season-prev\\]'si", "" );
		        }
		        else {
		            $tpl->set( '{season-prev}', '' );
                    $tpl->set( '[not-season-prev]', '' );
                    $tpl->set( '[/not-season-prev]', '' );
                    $tpl->set_block( "'\\[season-prev\\](.*?)\\[/season-prev\\]'si", "" );
		        }
            }
            else {
                $tpl->set( '{season-prev}', '' );
                $tpl->set( '[not-season-prev]', '' );
                $tpl->set( '[/not-season-prev]', '' );
                $tpl->set_block( "'\\[season-prev\\](.*?)\\[/season-prev\\]'si", "" );
            }
            if ( $nearest_seasons['next'] !== null ) {
                
                if ( isset($episodes_list[$nearest_seasons['next']]['date']) && $episodes_list[$nearest_seasons['next']]['date'] > $_TIME && $series_options['future'] == 1 ) $disable_next_season = true;
                else $disable_next_season = false;
                
                if ( $disable_next_season === false ) {
                    $tpl->set( '{season-next}', ksep_generate_links($full_link, $nearest_seasons['next'], false) );
                    $tpl->set( '[season-next]', '' );
                    $tpl->set( '[/season-next]', '' );
                    $tpl->set_block( "'\\[not-season-next\\](.*?)\\[/not-season-next\\]'si", "" );
                }
                else {
                    $tpl->set( '{season-next}', '' );
                    $tpl->set( '[not-season-next]', '' );
                    $tpl->set( '[/not-season-next]', '' );
                    $tpl->set_block( "'\\[season-next\\](.*?)\\[/season-next\\]'si", "" );
                }
            }
            else {
                $tpl->set( '{season-next}', '' );
                $tpl->set( '[not-season-next]', '' );
                $tpl->set( '[/not-season-next]', '' );
                $tpl->set_block( "'\\[season-next\\](.*?)\\[/season-next\\]'si", "" );
            }
		    
		    $compare_date_szn = compare_days_date( $episodes_list[$current_season]['date'] );
		    if( !$compare_date_szn ) $tpl->set( '{season-date}', $lang['time_heute'] . langdate( ", H:i", $episodes_list[$current_season]['date'] ) );
		    elseif( $compare_date_szn == 1 ) $tpl->set( '{season-date}', $lang['time_gestern'] . langdate( ", H:i", $episodes_list[$current_season]['date'] ) );
		    else $tpl->set( '{season-date}', langdate( $config['timestamp_active'], $episodes_list[$current_season]['date'] ) );
		    
		    $news_date = $episodes_list[$current_season]['date'];
		    $tpl->copy_template = preg_replace_callback ( "#\{season-date=(.+?)\}#i", "formdate", $tpl->copy_template );
		    
		    $tpl->set( '{season-num}', $current_season );
		    $tpl->set( '{season-link}', ksep_generate_links($full_link, $current_season, false) );
		    
		    if ( $episodes_list[$current_season]['shorturl'] == 0 ) {
		        $tpl->set( '[season-link]', '' );
                $tpl->set( '[/season-link]', '' );
                $tpl->set_block( "'\\[not-season-link\\](.*?)\\[/not-season-link\\]'si", "" );
		    }
		    else {
		        $tpl->set( '[not-season-link]', '' );
                $tpl->set( '[/not-season-link]', '' );
                $tpl->set_block( "'\\[season-link\\](.*?)\\[/season-link\\]'si", "" );
		    }
		    
		    if ( count($season_xfields) > 0 ) {
		        if ( $episodes_list[$current_season]['fields'] ) $xfields_season = xfieldsdataload($episodes_list[$current_season]['fields']);
		        else $xfields_season = [];
		        foreach ( $season_xfields as $sezfieldname => $sezfieldkind ) {
		            if ( $sezfieldkind == 'text' || $sezfieldkind == 'textarea' ) {
		                if ( isset($xfields_season[$sezfieldname]) && $xfields_season[$sezfieldname] ) {
		                    $tpl->set( '{season-field-'.$sezfieldname.'}', $xfields_season[$sezfieldname] );
		                    $tpl->set( '[season-field-'.$sezfieldname.']', '' );
		                    $tpl->set( '[/season-field-'.$sezfieldname.']', '' );
		                    $tpl->set_block( "'\\[not-season-field-".$sezfieldname."\\](.*?)\\[/not-season-field-".$sezfieldname."\\]'si", "" );
		                }
		                else {
		                    $tpl->set( '{season-field-'.$sezfieldname.'}', '' );
		                    $tpl->set( '[not-season-field-'.$sezfieldname.']', '' );
		                    $tpl->set( '[/not-season-field-'.$sezfieldname.']', '' );
		                    $tpl->set_block( "'\\[season-field-".$sezfieldname."\\](.*?)\\[/season-field-".$sezfieldname."\\]'si", "" );
		                }
		            }
		            elseif ( $sezfieldkind == 'image' ) {
		                if ( isset($xfields_season[$sezfieldname]) && $xfields_season[$sezfieldname] ) {
		                    
		                    $temp_array = explode('|', $xfields_season[$sezfieldname]);
						
					        if (count($temp_array) == 1 OR count($temp_array) == 5 ){
						        $temp_alt = '';
						        $temp_value = implode('|', $temp_array );
					        } else {
						        $temp_alt = $temp_array[0];
						        $temp_alt = str_replace( "&amp;#44;", "&#44;", $temp_alt );
						        $temp_alt = str_replace( "&amp;#124;", "&#124;", $temp_alt );
						
						        unset($temp_array[0]);
						        $temp_value =  implode('|', $temp_array );
					        }

					        $path_parts = get_uploaded_image_info($temp_value);
		                    
		                    if( $series_options['fields']['season'][$sezfieldname][12] AND $path_parts->thumb ) {
						        $tpl->set( '{season-field-'.$sezfieldname.'_thumb_url}', $path_parts->thumb);
						        $xfields_season[$sezfieldname] = "<a href=\"{$path_parts->url}\" data-highslide=\"single\" target=\"_blank\"><img class=\"xfieldimage {$sezfieldname}\" src=\"{$path_parts->thumb}\" alt=\"{$temp_alt}\"></a>";
					        } else {
						        $tpl->set( '{season-field-'.$sezfieldname.'_thumb_url}', $path_parts->url);
						        $xfields_season[$sezfieldname] = "<img class=\"xfieldimage {$sezfieldname}\" src=\"{$path_parts->url}\" alt=\"{$temp_alt}\">";
					        }
		                    
		                    if ( isset($schema_images)) $schema_images[] = $path_parts->url;
					
					        $tpl->set( '{season-field-'.$sezfieldname.'_image_url}', $path_parts->url);
					        $tpl->set( '{season-field-'.$sezfieldname.'_image_description}', $temp_alt);
		                    
		                    $tpl->set( '{season-field-'.$sezfieldname.'}', $xfields_season[$sezfieldname] );
		                    $tpl->set( '[season-field-'.$sezfieldname.']', '' );
		                    $tpl->set( '[/season-field-'.$sezfieldname.']', '' );
		                    $tpl->set_block( "'\\[not-season-field-".$sezfieldname."\\](.*?)\\[/not-season-field-".$sezfieldname."\\]'si", "" );
		                }
		                else {
		                    $tpl->set( '{season-field-'.$sezfieldname.'}', '' );
		                    $tpl->set( '[not-season-field-'.$sezfieldname.']', '' );
		                    $tpl->set( '[/not-season-field-'.$sezfieldname.']', '' );
		                    $tpl->set_block( "'\\[season-field-".$sezfieldname."\\](.*?)\\[/season-field-".$sezfieldname."\\]'si", "" );
		                    $tpl->set( '{season-field-'.$sezfieldname.'_image_url}', '' );
		                    $tpl->set( '{season-field-'.$sezfieldname.'_image_description}', '' );
		                    $tpl->set( '{season-field-'.$sezfieldname.'_thumb_url}', '' );
		                }
		            }
		        }
		    }
		    
		    //Ссылки на все сезоны
		    $seasons_links = $one_more_season = '';
            $seasons_links_tpl = file_get_contents(TEMPLATE_DIR.'/kodik_episodes/seasons_links.tpl');
            if ( $series_options['season']['sort_seasons'] == 1 ) krsort($episodes_list);
            else ksort($episodes_list);
		    foreach ( $episodes_list as $season_num => $season ) {
				$one_more_season .= '123';
                if ( $season['approve'] == 0 ) continue;
                if ( $season['shorturl'] == 1 ) continue;
                if ( $series_options['future'] == 1 && $season['date'] > $_TIME ) continue;
                $seasons_links .= str_replace(['{season-link}', '{season-num}'], [ksep_generate_links($full_link, $season_num, false), $season_num], $seasons_links_tpl);
                if ( $season_num == $current_season ) $seasons_links = preg_replace( "'\[season-active\](.*?)\[/season-active\]'si", "\\1", $seasons_links );
                else $seasons_links = preg_replace( "'\[season-active\](.*?)\[/season-active\]'si", "", $seasons_links );
            }
            if ( !empty($seasons_links) ) {
                $tpl->set( '{seasons-links}', $seasons_links );
                $tpl->set( '[seasons-links]', '' );
                $tpl->set( '[/seasons-links]', '' );
            }
            else {
                $tpl->set_block( "'\\[seasons-links\\](.*?)\\[/seasons-links\\]'si", "" );
                $tpl->set( '{seasons-links}', '' );
            }
            
            //Ссылки на все серии текущего сезона
            $episodes_links = '';
            $episodes_links_tpl = file_get_contents(TEMPLATE_DIR.'/kodik_episodes/episodes_links.tpl');
            if ( $series_options['season']['sort_episodes'] == 1 ) krsort($episodes_list[$current_season]['episodes']);
            else ksort($episodes_list[$current_season]['episodes']);
            foreach ( $episodes_list[$current_season]['episodes'] as $episode_num => $episode ) {
                if ( $episode['approve'] == 0 ) continue;
                if ( $series_options['require_players'] == 1 && !$episode['players'] ) continue;
                if ( $series_options['future'] == 1 && $episode['date'] > $_TIME ) continue;
                if ( $episodes_list[$current_season]['shorturl'] == 1  || $one_more_season == '123' || $one_more_season == '') {
					$episodes_links .= str_replace(['{episode-link}', '{episode-num}', '{season-num}'], [ksep_generate_links($full_link, false, $episode_num), $episode_num, $first_season_key], $episodes_links_tpl);
                } else {
					$episodes_links .= str_replace(['{episode-link}', '{episode-num}', '{season-num}'], [ksep_generate_links($full_link, $current_season, $episode_num), $episode_num, $current_season], $episodes_links_tpl);
                }
				if ( $episode_num == $current_episode ) $episodes_links = preg_replace( "'\[episode-active\](.*?)\[/episode-active\]'si", "\\1", $episodes_links );
                else $episodes_links = preg_replace( "'\[episode-active\](.*?)\[/episode-active\]'si", "", $episodes_links );
            }
            if ( !empty($episodes_links) ) {
                $tpl->set( '{episodes-links}', $episodes_links );
                $tpl->set( '[episodes-links]', '' );
                $tpl->set( '[/episodes-links]', '' );
            }
            else {
                $tpl->set_block( "'\\[episodes-links\\](.*?)\\[/episodes-links\\]'si", "" );
                $tpl->set( '{episodes-links}', '' );
            }
		    
        }
        
    }
}

if ( $ksep_founded === false ) {
    header("HTTP/1.0 301 Moved Permanently");
	header("Location: {$full_link}");
	die("Redirect");
}