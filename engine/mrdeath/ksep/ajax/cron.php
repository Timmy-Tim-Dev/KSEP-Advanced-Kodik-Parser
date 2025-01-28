<?php

ini_set("memory_limit","512M");
ini_set('max_execution_time',600);
ignore_user_abort(true);
set_time_limit(600);
session_write_close();

include_once ENGINE_DIR . '/mrdeath/ksep/data/config.php';

@header('Content-type: text/html; charset=' . $config['charset']);

date_default_timezone_set($config['date_adjust']);

$user_group = array ();
	
$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
while ( $row = $db->get_row() ) {
		
	$user_group[$row['id']] = array ();
		
	foreach ( $row as $key => $value ) {
		$user_group[$row['id']][$key] = stripslashes($value);
	}
	
}
set_vars( "usergroup", $user_group );
$db->free();
	
$main_userid = 1;

$member_id = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE user_id=".$main_userid);
set_vars('member_id', $member_id);

$action = isset($_GET['action']) ? $_GET['action'] : 'generate';

if ( isset($_GET['key']) && $_GET['key'] != $series_options['cron_key'] ) die('Cron secret key is wrong');
elseif ( !isset($_GET['key']) ) die('Cron secret key is empty');

if (!function_exists('xfieldsdatasaved')) {
    function xfieldsdatasaved($xfields) {
        $filecontents = [];
        foreach ($xfields as $xfielddataname => $xfielddatavalue) {
            if ($xfielddatavalue === '') continue;
            $xfielddataname = str_replace( "|", "&#124;", $xfielddataname);
            $xfielddataname = str_replace( "\r\n", "__NEWL__", $xfielddataname);
            $xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue);
            $xfielddatavalue = str_replace( "\r\n", "__NEWL__", $xfielddatavalue);
            $filecontents[] = $xfielddataname."|".$xfielddatavalue;
        }
        $filecontents = join('||', $filecontents );
        return $filecontents;
    }
}

if ( $action == 'generate' ) {
    $ksep_material = $db->super_query( "SELECT * FROM " . PREFIX . "_ksep_cron ORDER by id ASC LIMIT 1" );
    if ( !$ksep_material['id'] ) die('На данный момент нет серий в очереди для генерации!');
    
    $rowid = $ksep_material['news_id'];
    $sez_num = $ksep_material['season'];
	$ep_num = $ksep_material['episode'];
	$ep_data = json_decode($ksep_material['data'], true);
	$sez_count = $ksep_material['season_count'];
	$material_title = $ksep_material['title'];
	
	$db->query("DELETE FROM " . PREFIX . "_ksep_cron WHERE id='{$ksep_material['id']}'");
	
	require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
	
    $_REQUEST['module'] = 'ksep';
    include_once(DLEPlugins::Check(ENGINE_DIR . '/classes/uploads/upload.class.php'));
    
    require_once ENGINE_DIR.'/mrdeath/ksep/modules/aap_ajax_episode.php';
    
}