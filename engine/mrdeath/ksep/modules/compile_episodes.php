<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
include ENGINE_DIR . '/mrdeath/ksep/data/config.php';

if ( isset($_GET['szn']) && !isset($_GET['epzd']) ) require_once ENGINE_DIR.'/mrdeath/ksep/modules/season_page.php';
elseif ( isset($_GET['epzd']) ) require_once ENGINE_DIR.'/mrdeath/ksep/modules/episode_page.php';
else require_once ENGINE_DIR.'/mrdeath/ksep/modules/fullstory_page.php';