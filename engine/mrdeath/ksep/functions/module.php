<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

function get_fields_box($type, $season = false, $episode = false, $datafields = '') {
	global $series_options;

	if ( $season && $episode ) {
		$prefix = '[' . $season . '][episodes][' . $episode . ']';
		$id = $season . '_' . $episode;
	} elseif ( $season ) {
		$prefix = '[' . $season . ']';
		$id = $season;
	} else {
		$prefix = ($type == 'season') ? '[{num}]' : '[{snum}][episodes][{num}]';
		$id = ($type == 'season') ? '{num}' : '{snum}_{num}';
	}

	$datafields = xfieldsdataload(stripslashes($datafields));

	$buffer = '';

	foreach ($series_options['fields'][$type] as $name => $field) {
		$value = (!empty($datafields[$name])) ? $datafields[$name] : '';

		$fid = "sp_{$type}_{$name}_field_{$id}";

		switch ($field[3]) {
			case 'text':
				$buffer .= <<<HTML
<div class="sp-item-field">
	<label>{$field[1]}</label>
	<input type="text" name="seriespages{$prefix}[fields][{$name}]" class="form-control" id="{$fid}" value="{$value}">
</div>
HTML;
				break;

			case 'textarea':
				$buffer .= <<<HTML
<div class="sp-item-field">
	<label>{$field[1]}</label>
	<textarea name="seriespages{$prefix}[fields][{$name}]" class="form-control" id="{$fid}" style="height: 100px;">{$value}</textarea>
</div>
HTML;

				break;

			case 'image':
				$thumb = '0';

				if ( $value ) {
					if ( file_exists(ROOT_DIR . '/uploads/posts/' . str_replace('/', '/thumbs/', $value)) ) {
						$thumb = '1';
					}
				}
                
                $max_size = $field[9] ? $field[9] : 0;
				$make_watermark = $field[11] ? '1' : '0';
				$make_thumb = $field[12] ? '1' : '0';
				$thumb_size = $field[13] ? $field[13] : 0;
				$thumb_seite = intval($field['13_seite']);

				$buffer .= <<<HTML
<div class="sp-item-field">
	<label>{$field[1]}</label>
	<div id="{$fid}_upload"></div>
	<input type="hidden" name="seriespages{$prefix}[fields][{$name}]" value="{$value}" data-max_size="{$max_size}" data-thumb="{$thumb}" data-make_watermark="{$make_watermark}" data-make_thumb="{$make_thumb}" data-thumb_size="{$thumb_size}" data-thumb_seite="{$thumb_seite}" data-ksep_type="{$type}" data-ksep_name="{$name}" id="{$fid}" class="sp_uploader_start" />
</div>
HTML;

				break;

			default:
				# code...
				break;
		}
	}

	return $buffer;
}

function fieldscompile( $in ) {
    if ( $in == "" ) return $in;
    
    $out = [];

    foreach ( $in as $name => $value ) {
        if ( trim($name) == "" || trim($value) == "" ) continue;

        $name = str_replace( "|", "&#124;", $name );
        $name = str_replace( "\r\n", "__NEWL__", $name );
        $value = str_replace( "|", "&#124;", $value );
        $value = str_replace( "\r\n", "__NEWL__", $value );

        $out[] = "{$name}|{$value}";
    }

    if ( is_array($out) && $out ) return implode("||", $out);
    else return '';
}

function parse_str_unlimited($string, &$result) {
    if ( $string === '') return false;

    $result = [];
    $pairs = explode('&', $string);
    $params = [];

    foreach ($pairs as $pair) {
        parse_str($pair, $params);

        $k = key($params);

        if ( !isset($result[$k]) ) {
            $result += $params;
        } else {
            $result[$k] = array_merge_recursive_distinct($result[$k], $params[$k], stripos($pair, '%5B%5D=') !== false);
        }
    }

    return true;
}

function array_merge_recursive_distinct ($array1, $array2, $is_array = false) {
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if ( is_array($value) && is_array($merged[$key]) ) {
        	if ( isset($merged[$key]) ) {
        		$merged[$key] = array_merge_recursive_distinct($merged[$key], $value, $is_array);
        	} else {
        		$merged[$key] += $value;
        	}
        } else {
        	if ( !is_array($value) && $is_array ) {
        		$merged[] = $value;
        	} else {
        		$merged[$key] = $value;
        	}
        }
    }

    return $merged;
}

function ksep_read($prefix) {
	global $config, $is_logged, $member_id, $dlefastcache;
  
  	if( !is_dir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" ) ) {
        @mkdir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
        @chmod( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
    }

	$buffer = @file_get_contents( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" . $prefix . ".json" );

	return $buffer;

}

function ksep_create($prefix, $cache_text) {
	global $config, $is_logged, $member_id, $dlefastcache;
  
  	if( !is_dir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" ) ) {
        @mkdir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
        @chmod( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
    }
	
	if($cache_text === false) $cache_text = '';

	file_put_contents (ENGINE_DIR . "/mrdeath/ksep/episodes_list/" . $prefix . ".json", $cache_text, LOCK_EX);
	@chmod( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" . $prefix . ".json", 0666 );
	
	return true;
	
}

function ksep_delete($cache_name = false) {
	global $dlefastcache, $config;
  
  	if( !is_dir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" ) ) {
        @mkdir( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
        @chmod( ENGINE_DIR . "/mrdeath/ksep/episodes_list/", 0777 );
    }

	@unlink( ENGINE_DIR . "/mrdeath/ksep/episodes_list/" . $cache_name . ".json" );
	
	return true;

}

function ksep_generate_links($link, $season = false, $episode = false) {
	
	if ( $season !== false && $episode !== false ) $generated_link = str_replace('.html', '/season-'.$season.'/episode-'.$episode.'.html', $link);
	elseif ( $season !== false ) $generated_link = str_replace('.html', '/season-'.$season.'.html', $link);
	elseif ( $episode !== false ) $generated_link = str_replace('.html', '/episode-'.$episode.'.html', $link);
	else $generated_link = $link;
	
	return $generated_link;

}

function findNearestKeys($arr, $target) {
    // Преобразуем ключи массива в массив числовых значений
    $keys = array_keys($arr);
    $numericKeys = [];
    
    foreach ($keys as $key) {
        list($start, $end) = explode('-', $key);
        $numericKeys[$key] = (int)$start;
    }

    // Сортируем ключи по числовым значениям
    asort($numericKeys);

    $keys = array_keys($numericKeys);
    $count = count($keys);
    $left = null;
    $right = null;

    // Ищем позицию целевого ключа
    $targetPosition = array_search($target, $keys);

    if ($targetPosition !== false) {
        // Находим ближайшие ключи слева и справа
        if ($targetPosition > 0) {
            $left = $keys[$targetPosition - 1];
        }
        if ($targetPosition < $count - 1) {
            $right = $keys[$targetPosition + 1];
        }
    }

    return ['prev' => $left, 'next' => $right];
}

if (!function_exists('ksepPoster')) {
    function ksepPoster($poster_url, $poster_title, $poster_name = false, $news_id = 0) {
		
        global $config, $series_options, $db, $member_id, $user_group;

        $area = 'xfieldsimage';
		
	    if ( $poster_name ) {
	    	$xfparam = $series_options['fields']['episode'][$poster_name];
	    }
	    else $xfparam = [];
		
		$_REQUEST['xfname'] = $xfparam[0];
	    $t_seite = $m_seite = intval($config['t_seite']);
	    if ( isset($xfparam[13]) && $xfparam[13] ) $t_size = $xfparam[13];
	    else $t_size = 0;
		$m_size = 0;
		$config['max_up_side'] = $xfparam[9];
		$config['max_up_size'] = $xfparam[10];
		$config['min_up_side'] = $xfparam[22];
		$make_watermark = $xfparam[11] ? true : false;
		$make_thumb = $xfparam[12] ? true : false;
		$make_medium = false;
		$hidpi = false;

	    $t_size = explode("x", $t_size);
	    if (count($t_size) == 2) {
	    	$t_size = intval($t_size[0]) . "x" . intval($t_size[1]);
	    } else $t_size = intval($t_size[0]);

	    $m_size = explode("x", $m_size);
	    if (count($m_size) == 2) {
	    	$m_size = intval($m_size[0]) . "x" . intval($m_size[1]);
	    } else $m_size = intval($m_size[0]);

        $author = $db->safesql($member_id['name']);
		
        $temp_dir = ROOT_DIR . "/uploads/posts/" . date( "Y-m" ) .'/';
        
        if( !is_dir( $temp_dir ) ) {
            @mkdir( $temp_dir, 0777 );
            @chmod( $temp_dir, 0777 );
        }
        else @chmod( $temp_dir, 0777 );
        
        if( !is_dir( $temp_dir.'thumbs/' ) ) {
            @mkdir( $temp_dir.'thumbs/', 0777 );
            @chmod( $temp_dir.'thumbs/', 0777 );
        }
        else @chmod( $temp_dir.'thumbs/', 0777 );
        
        if( !is_dir( $temp_dir.'medium/' ) ) {
            @mkdir( $temp_dir.'medium/', 0777 );
            @chmod( $temp_dir.'medium/', 0777 );
        }
        else @chmod( $temp_dir.'medium/', 0777 );
            
        $poster_title = totranslit(stripslashes( $poster_title ), true, false);
        
        $image = downloadImage($poster_url, $poster_title);
            
        if ( isset($image) && $image ) {
            $exif = exif_read_data($image);
            $_FILES['qqfile'] = [
                'type' => $exif['MimeType'],
                'name' => $exif['FileName'],
                'tmp_name' => $image,
                'error' => 0,
                'size' => $exif['FileSize']
            ];
            
            $uploader = new FileUploader($area, $news_id, $author, $t_size, $t_seite, $make_thumb, $make_watermark, $m_size, $m_seite, $make_medium, $hidpi);
			$result = json_decode($uploader->FileUpload(), true);

            @unlink($image);
            return $result;
        }
        else {
            @unlink($image);
            return '';
        }
    }
}

if (!function_exists('downloadImage')) {
    function downloadImage($imageUrl, $newFileName) {
        // Устанавливаем директорию для загрузки
        $uploadDir = ROOT_DIR . '/uploads/files/';

        // Создаем директорию, если она не существует
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            chmod($uploadDir, 0777);
        }

        // Используем cURL для загрузки изображения
        $ch = curl_init($imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60 );
        curl_setopt($ch, CURLOPT_TIMEOUT, 60 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $imageData = curl_exec($ch);
        if(curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return false;
        }
        curl_close($ch);

        // Определяем расширение файла на основе MIME-типа
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);
        $extension = '';

        switch ($mimeType) {
            case 'image/jpeg':
                $extension = 'jpeg';
                break;
            case 'image/jpg':
                $extension = 'jpg';
                break;
            case 'image/png':
                $extension = 'png';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            case 'image/webp':
                $extension = 'webp';
                break;
            default:
                return false; // неподдерживаемый тип изображения
        }

        // Генерируем новое имя файла с расширением
        $newFileNameWithExtension = $newFileName . '.' . $extension;

        // Путь к новому файлу
        $newFilePath = $uploadDir . $newFileNameWithExtension;

        // Сохраняем изображение в директорию
        file_put_contents($newFilePath, $imageData);
        chmod($newFilePath, 0777);

        // Возвращаем путь к новому файлу
        return $newFilePath;
    }
}

if (!function_exists('compare_days_date')) {
function compare_days_date( $news_date,  $servertime = false ) {
	global $_TIME, $member_id;

	if (!$news_date) {
		$news_date = time();
	}

	$newsdate = new DateTime('@' . $news_date);
	$nowdate   = new DateTime('@' . $_TIME);
	$yesterdaydate = new DateTime('-1 day');

	if (isset($member_id['timezone']) and $member_id['timezone'] and !$servertime) {
		$localzone = $member_id['timezone'];
	} else {
		$localzone = date_default_timezone_get();
	}

	if ( !in_array( $localzone, DateTimeZone::listIdentifiers() ) ) $localzone = 'Europe/Moscow';

	$newsdate->setTimeZone(new DateTimeZone($localzone));
	$nowdate->setTimeZone(new DateTimeZone($localzone));
	$yesterdaydate->setTimeZone(new DateTimeZone($localzone));

	$diff = $newsdate->diff($nowdate);
	$days = intval($diff->format('%a'));

	if( $newsdate->format('Ymd') == $yesterdaydate->format('Ymd') ) {
		return 1;
	}

	return $days;

}
}
