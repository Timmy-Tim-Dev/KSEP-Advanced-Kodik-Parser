<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

if ( $news_found ) {
    if (preg_match('#\\[(kodik-metatags)\\](.+?)\\[/\\1\\]#is', $tpl->result['content'], $kodik_meta)) {
	    preg_match_all('#<(.+?)>(.*?)</\\1>#is', $kodik_meta[2], $kodik_tags);
	    foreach ($kodik_tags[1] as $k => $v) {
		    $val = preg_replace('#\s+#is', ' ', $kodik_tags[2][$k]);
		    $val = strip_tags($val);
		    $val = trim($val);
		    if (stripos($v, 'og:') === 0) {
			    $v = substr($v, 3);
			    $social_tags[$v] = $val;
		    }
		    elseif ($v != 'title' || !$metatags['header_title']) {
			    $v == 'title' && $v = 'header_title';
			    $metatags[$v] = $val;
        	    if ($v == 'robots' && in_array($val, ['0','no','off','false'])) {
          		    $disable_index = true;
				    unset($metatags[$v]);
			    }
		    }
	    }
	    $tpl->result['content'] = str_replace($kodik_meta[0], '', $tpl->result['content']);
	    $tpl->result['content'] = trim($tpl->result['content']);
    }
}