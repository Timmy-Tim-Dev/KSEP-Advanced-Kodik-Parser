<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

require_once ENGINE_DIR.'/mrdeath/ksep/data/config.php';
require_once ENGINE_DIR.'/mrdeath/aaparser/functions/module.php';

if ( isset($aaparser_config['settings']['kodik_api_key']) ) $kodik_apikey = $aaparser_config['settings']['kodik_api_key'];
else $kodik_apikey = '9a3a536a8be4b3d3f9f7bd28c1b74071';

if ( isset($aaparser_config['settings']['kodik_api_domain']) ) $kodik_api_domain = $aaparser_config['settings']['kodik_api_domain'];
else $kodik_api_domain = 'https://kodikapi.com/';

if ( isset($_GET['action']) && $_GET['action'] ) $action = $_GET['action'];
else if ( isset($_GET['action']) && $_GET['action'] ) $action = $_GET['action'];
else $action = null;

@header('Content-type: text/html; charset=' . $config['charset']);

date_default_timezone_set($config['date_adjust']);
$_TIME = time();

if (!$is_logged) {
	$member_id['user_group'] = 5;
}

if ($is_logged && $member_id['banned'] == 'yes') {
	die('User banned');
}

$user_group = get_vars('usergroup');

if ( $action == "update_news_get" ) {
		
		if ( !$aaparser_config['main_fields']['xf_shikimori_id'] && !$aaparser_config['main_fields']['xf_mdl_id'] ) {
	        die(json_encode(array(
		        'status' => 'fail'
	        )));
	    }
	
	    if ( $aaparser_config['main_fields']['xf_shikimori_id'] && $aaparser_config['main_fields']['xf_mdl_id'] ) $where = "(xfields LIKE '%|".$aaparser_config['main_fields']['xf_shikimori_id']."|%' OR xfields LIKE '%|".$aaparser_config['main_fields']['xf_mdl_id']."|%' OR xfields LIKE '%".$aaparser_config['main_fields']['xf_shikimori_id']."|%' OR xfields LIKE '%".$aaparser_config['main_fields']['xf_mdl_id']."|%') AND xfields LIKE '%|".$aaparser_config['main_fields']['xf_series']."|%'";
	    elseif ( $aaparser_config['main_fields']['xf_shikimori_id'] ) $where = "(xfields LIKE '%|".$aaparser_config['main_fields']['xf_shikimori_id']."|%' OR xfields LIKE '%".$aaparser_config['main_fields']['xf_shikimori_id']."|%') AND xfields LIKE '%|".$aaparser_config['main_fields']['xf_series']."|%'";
	    else $where = "(xfields LIKE '%|".$aaparser_config['main_fields']['xf_mdl_id']."|%' OR xfields LIKE '%".$aaparser_config['main_fields']['xf_mdl_id']."|%') AND xfields LIKE '%|".$aaparser_config['main_fields']['xf_series']."|%'";
	    $news = $db->query( "SELECT id, xfields FROM " . PREFIX . "_post WHERE ".$where );
		
		$news_count = $news->num_rows;
		if($news_count == 0) return;
		$result_connect = array();
		$count = 0;
		
		while($temp_news = $db->get_row($news)) {
			$id = intval($temp_news['id']);
			$xfields = xfieldsdataload($temp_news['xfields']);
			if ( $xfields[$aaparser_config['main_fields']['xf_shikimori_id']] ) $shikimori_id = $xfields[$aaparser_config['main_fields']['xf_shikimori_id']];
			else $shikimori_id = 0;
			if ( $xfields[$aaparser_config['main_fields']['xf_mdl_id']] ) $mdl_id = $xfields[$aaparser_config['main_fields']['xf_mdl_id']];
			else $mdl_id = 0;

			if (!$shikimori_id && !$mdl_id) continue;			
			
			$result_connect[] = array(
				'id' => $id,
				'shikimori_id' => $shikimori_id,
				'mdl_id' => $mdl_id,
			);
			
			$count++;
		}
		if ($count > 0)
			echo json_encode($result_connect);
		else
			die(json_encode(array(
		        'status' => 'fail'
	        )));
}
elseif ( $action == "update_news" ) {
	
	if ( !isset($aaparser_config['main_fields']['xf_shikimori_id']) && !isset($aaparser_config['main_fields']['xf_mdl_id']) ) {
	   die(json_encode(array(
		  'status' => 'fail'
	   )));
	}
	    
	$rowid = $_GET['newsid'];
    $rowid = is_numeric($rowid) ? intval($rowid) : false;
    
    if(!$rowid) die(json_encode(array(
	    'error' => 'Не передан id новости'
	)));
    
	if ( $_GET['shikiid'] && $_GET['shikiid'] != 0 && $_GET['shikiid'] != '0' ) $shikiid = $_GET['shikiid'];
	else $shikiid = 0;
	if ( $_GET['mdlid'] && $_GET['mdlid'] != 0 && $_GET['mdlid'] != '0' ) $mdlid = $_GET['mdlid'];
	else $mdlid = 0;
	
	if( !$shikiid && !$mdlid ) die(json_encode(array(
	    'error' => 'Не передан shikimori или mdl id'
	)));
	
	$news_row = $db->super_query( "SELECT id FROM " . PREFIX . "_post WHERE id='{$rowid}'" );
	if ( !$news_row['id'] ) return;
	
	require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
	
	require_once ENGINE_DIR.'/mrdeath/ksep/modules/aap_ajax.php';

}
elseif ( $action == "update_news_episode" ) {
	
	if ( !$_GET['newsid'] && !$_GET['sez_num'] && !$_GET['ep_num'] ) {
	   die(json_encode(array(
		  'status' => 'fail'
	   )));
	}
	    
	$rowid = $_GET['newsid'];
    $rowid = is_numeric($rowid) ? intval($rowid) : false;
    
    if(!$rowid) die(json_encode(array(
	    'error' => 'Не передан id новости'
	)));
    
	$sez_num = $_GET['sez_num'];
	$ep_num = $_GET['ep_num'];
	$ep_data = $_GET['ep_data'];
	$sez_count = $_GET['sez_count'];
	$material_title = $_GET['material_title'];
	
	require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
	
	$_REQUEST['module'] = 'ksep';
	include_once(DLEPlugins::Check(ENGINE_DIR . '/classes/uploads/upload.class.php'));
	
	require_once ENGINE_DIR.'/mrdeath/ksep/modules/aap_ajax_episode.php';

}
elseif ( $action == "generate_eps" ) {
		
	if ( !$aaparser_config['main_fields']['xf_shikimori_id'] && !$aaparser_config['main_fields']['xf_mdl_id'] ) {
	    die(json_encode(array(
	        'status' => 'fail'
	    )));
	}
	    
	$rowid = $_GET['newsid'];
    $rowid = is_numeric($rowid) ? intval($rowid) : false;
    
    if(!$rowid) die(json_encode(array(
	    'error' => 'Не передан id новости'
	)));
	
	$temp_news = $db->super_query( "SELECT id, xfields FROM " . PREFIX . "_post WHERE id='".$rowid."'" );
		
	if(!$temp_news['id']) die(json_encode(array(
	    'error' => 'Данной новости не существует'
	)));
	$result_connect = array();
	$count = 0;
		
	$xfields = xfieldsdataload($temp_news['xfields']);
	if ( $xfields[$aaparser_config['main_fields']['xf_shikimori_id']] ) $shikiid = $xfields[$aaparser_config['main_fields']['xf_shikimori_id']];
	else $shikiid = false;
	if ( $xfields[$aaparser_config['main_fields']['xf_mdl_id']] ) $mdlid = $xfields[$aaparser_config['main_fields']['xf_mdl_id']];
	else $mdlid = false;

	if (!$shikiid && !$mdlid) die(json_encode(array(
	    'error' => 'Не передан id shikimori или mydramalist новости'
	)));
			
	require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
	
	require_once ENGINE_DIR.'/mrdeath/ksep/modules/aap_ajax.php';
	
}

?>
