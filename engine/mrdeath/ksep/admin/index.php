<?php 

(defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

if ( $member_id['user_group'] != 1  ) {
	if ( $action == "save" && count($_REQUEST['options']) ) die('{"success":false,"message":"Доступ запрещен"}');
		else msg('error', $lang['index_denied'], $lang['index_denied']);
}

function ShowItem($name, $title, $descr, $value = '', $type = 'text', $values = array(), $value_attr = true) {
    if ( !is_array($value) && count($values) ) $value = array($value);

    $options = array();

    foreach ($values as $option_value => $option_name) {
    	$option_val = ($value_attr) ? ' value="' . $option_value . '"' : '';
    	$selected = (in_array(($value_attr ? $option_value : $option_name), $value) ? ' selected' : '');
        $options[] = '<option'. $option_val . $selected . '>' . $option_name . '</option>';
    }

    switch ($type) {
        case 'text':
            $field = '<input type="text" class="form-control" name="' . $name . '" value="' . $value . '">';
            break;

        case 'number':
            $field = '<input type="number" class="form-control" name="' . $name . '" value="' . intval($value) . '">';
            break;

        case 'textarea':
            $field = '<textarea class="form-control" name="' . $name . '">' . $value . '</textarea>';
            break;

        case 'multiselect':
            $field = '<select name="' . $name . '" title=" " data-placeholder=" " multiple>' . implode("", $options) .  '</select>';
            break;

        case 'select':
            $field = '<select name="' . $name . '" class="uniform">' . implode("", $options) .  '</select>';
            break;

        case 'checkbox':
            $field = '<input class="switch" type="checkbox" name="' . $name . '" value="1"' . ($value == '1' ? ' checked' : '') . '>';
            break;

        default:
            $field = '';
            break;
    }

    return '<tr><td class="col-xs-6 col-sm-6 col-md-7"><h6 class="media-heading text-semibold">' . $title . '</h6><span class="text-muted text-size-small hidden-xs">' . $descr . '</span></td><td class="col-xs-6 col-sm-6 col-md-5">' . $field . '</td></tr>';
}

function save_con( $filename, $data, $iconv = false, $openfile = false, $prefix = "" ) {
	if( !$openfile ) {
		$handler = fopen( $filename, "w" );
		fwrite( $handler, "<?php\n\n\$series_options = [\n" );
	} else $handler = $openfile;

	foreach ( $data as $name => $value ) {
		$name = addcslashes( $name, "'" );
		if( $iconv ) $name = iconv( 'utf-8', 'cp1251', $name );

		if ( is_array( $value ) ) {
			fwrite( $handler, $prefix . "\t'{$name}' => [\n" );
			save_con( $filename, $value, $iconv, $handler, $prefix . "\t" );
			fwrite( $handler, $prefix . "\t],\n" );
		} else {
			$value = addcslashes( $value, "'" );
			if( $iconv ) $value = iconv( 'utf-8', 'cp1251', $value );

			fwrite( $handler, $prefix . "\t'{$name}' => '{$value}',\n" );
		}
	}

	if ( !$openfile ) {
		fwrite( $handler, "];\n\n?>" );
		fclose( $handler );
	}
}

$title = 'Series';
$descr = 'Сезоны/серии';

if (!file_exists(ENGINE_DIR.'/mrdeath/ksep/data/config.php')) {
$text = <<<HTML
<?PHP

\$series_options = [
	'require_players' => '1',
	'priority_season' => '0.5',
	'priority_episode' => '0.6',
	'main' => [
		'sort_seasons' => '0',
		'sort_episodes' => '0',
	],
	'season' => [
		'sort_seasons' => '0',
		'sort_episodes' => '0',
	],
	'aap' => [
		'kadr1_img' => 'kadr1',
		'kadr2_img' => 'kadr2',
		'kadr3_img' => 'kadr3',
		'kadr4_img' => 'kadr4',
		'kadr5_img' => 'kadr5',
		'translations' => 'translations',
	],
	'fields' => [
		'season' => [
			'plot' => [
				'0' => 'plot',
				'1' => 'Описание сезона',
				'18' => '',
				'3' => 'textarea',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'13' => '',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'5' => 'on',
				'21' => '',
			],
		],
		'episode' => [
			'kadr1' => [
				'0' => 'kadr1',
				'1' => 'Первый кадр',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'12' => 'on',
				'13' => '256x144',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
			'kadr2' => [
				'0' => 'kadr2',
				'1' => 'Второй кадр',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'12' => 'on',
				'13' => '256x144',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
			'kadr3' => [
				'0' => 'kadr3',
				'1' => 'Третий кадр',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'12' => 'on',
				'13' => '256x144',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
			'kadr4' => [
				'0' => 'kadr4',
				'1' => 'Четвёртый кадр',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'12' => 'on',
				'13' => '256x144',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
			'kadr5' => [
				'0' => 'kadr5',
				'1' => 'Пятый кадр',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'12' => 'on',
				'13' => '256x144',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
			'translations' => [
				'0' => 'translations',
				'1' => 'Доступные озвучки в серии',
				'18' => '',
				'3' => 'text',
				'4_text' => '',
				'4_textarea' => '',
				'4_select' => '',
				'16' => '',
				'13' => '',
				'13_seite' => '0',
				'14' => '',
				'15' => '',
				'17' => '0',
				'21' => '',
			],
		],
	],
];

?>
HTML;

  	$fp = fopen(ENGINE_DIR.'/mrdeath/ksep/data/config.php', "w+");
  	fwrite($fp, $text);
  	fclose($fp);
  	unset($text);
}

include ENGINE_DIR . '/mrdeath/ksep/data/config.php';

if ( !is_array($series_options) ) $series_options = [];

try {
	if ( !include DLEPlugins::Check(ENGINE_DIR . '/mrdeath/ksep/admin/' . ($action ? $action : 'main') . '.php') ) {
		 throw new Exception($action . '.php does not exist');
	}
} catch (Exception $e) {
	msg ( "error", $lang['index_denied'], $lang['mod_not_found'] );
}

echoheader('<i class="fa fa-bars icon-list position-left"></i><span class="text-semibold">' . $title . '</span>', $breadcrumbs);

echo $content;

echofooter();

?>
