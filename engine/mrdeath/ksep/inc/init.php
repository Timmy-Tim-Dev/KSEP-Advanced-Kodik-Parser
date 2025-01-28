<?php 

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR.'/mrdeath/ksep/functions/module.php';
include ENGINE_DIR . '/mrdeath/ksep/data/config.php';

$news_id = $id;
$author = $member_id['name'];

$start_box = <<<HTML
<div class="sp-head sp-head-main">
	<div class="sp-head-title">Сезоны</div>
	<div class="sp-head-actions">
		<a href="#" class="sp-head-btn sp-season-add"><i class="fa fa-plus"></i></a>
	</div>
</div>

<div class="sp-seasons">
	{list}
</div>
HTML;

$season_item = <<<HTML
<div class="sp-item" id="sp-season-{num}">
	<div class="sp-head sp-head-item">
		<div class="sp-head-switch"></div>
		<div class="sp-head-title" style="display: contents;">Сезон: <input type="text" name="seriespages[{num}][season_num]" value="{season_num}" class="form-control" style="max-width: 300px;"></div>
		<div class="sp-head-actions">
			<a href="#" class="sp-head-btn sp-season-remove position-left" title="Удалить сезон"><i class="fa fa-trash-o text-danger"></i></a>
			<a href="#" class="sp-head-btn sp-series-add" data-snum="{num}" title="Добавить серию"><i class="fa fa-plus"></i></a>
		</div>
	</div>

	<div class="sp-item-content">
		<div class="sp-item-tabs-wrap sp-item-fields">
			<ul class="sp-item-tabs">
				<li class="active">Серии</li>
				<li>Поля</li>
				<li>Дополнительно</li>
			</ul>

			<div class="sp-item-tabs-box visible sp-series">
				{list}
			</div>

			<div class="sp-item-tabs-box">
				{fields}
			</div>

			<div class="sp-item-tabs-box">
				<div class="sp-item-field">
					<label>Дата</label>
					<input type="text" data-rel="calendar" name="seriespages[{num}][date]" value="{date}" class="form-control position-left" style="width:190px;" autocomplete="off"><label class="checkbox-inline" style="width: auto;padding: 0px 0px 0px 28px;margin-top: 6px;"><input class="icheck" type="checkbox" name="seriespages[{num}][date_now]" value="1">установить текущую дату и время</label>
				</div>

				<div class="sp-item-field">
					<label class="checkbox-inline" style="width: auto;padding: 0px 0px 0px 28px;"><input type="checkbox" name="seriespages[{num}][approve]" value="1" {approve} class="icheck">Опубликовать на сайте</label>
				</div>

				[first]
				<div class="sp-item-field">
					<label class="checkbox-inline" style="width: auto;padding: 0px 0px 0px 28px;"><input type="checkbox" name="seriespages[{num}][shorturl]" value="1" {shorturl} class="icheck">Короткий URL серий(без сезона)</label>
				</div>
				[/first]
			</div>
		</div>
	</div>
</div>
HTML;

$episode_item = <<<HTML
<div class="sp-item" id="sp-episode-{num}">
	<div class="sp-head sp-head-item">
		<div class="sp-head-switch"></div>
		<div class="sp-head-title" style="display: contents;">Серия: <input type="text" name="seriespages[{snum}][episodes][{num}][episode_num]" value="{episode_num}" class="form-control" style="max-width: 300px;"></div>
		<div class="sp-head-actions">
			<a href="#" class="sp-head-btn sp-episode-remove" title="Удалить серию"><i class="fa fa-trash-o text-danger"></i></a>
		</div>
	</div>

	<div class="sp-item-content">
		<div class="sp-item-tabs-wrap sp-item-fields">
			<ul class="sp-item-tabs">
				<li class="active">Поля</li>
				<li>Плеера</li>
				<li>Дополнительно</li>
			</ul>

			<div class="sp-item-tabs-box visible">
				{fields}
			</div>

			<div class="sp-item-tabs-box">
				<div class="sp-players">
					<div class="dd">
						<ol class="dd-list sp-players-list">
							{players}
						</ol>
					</div>

					<a href="#" data-season="{snum}" data-episode="{num}" class="sp-player-add"><i class="fa fa-plus position-left"></i>Добавить плеер</a>
				</div>
			</div>

			<div class="sp-item-tabs-box">
				<div class="sp-item-field">
					<label>Дата</label>
					<input type="text" data-rel="calendar" name="seriespages[{snum}][episodes][{num}][date]" value="{date}" class="form-control position-left" style="width:190px;" autocomplete="off"><label class="checkbox-inline" style="width: auto;padding: 0px 0px 0px 28px;margin-top: 6px;"><input class="icheck" type="checkbox" name="seriespages[{snum}][episodes][{num}][date_now]" value="1">установить текущую дату и время</label>
				</div>

				<div class="sp-item-field">
					<label class="checkbox-inline" style="width: auto;padding: 0px 0px 0px 28px;"><input type="checkbox" name="seriespages[{snum}][episodes][{num}][approve]" value="1" {approve} class="icheck">Опубликовать на сайте</label>
				</div>
			</div>
		</div>
	</div>
</div>
HTML;

$player_item = '<li class="dd-item"><div class="dd-handle"></div><div class="dd-content"><input type="text" name="seriespages[{snum}][episodes][{num}][players][text][]" value="{text}" placeholder="Название" autocomplete="off" style="width: 25%;"><input type="text" name="seriespages[{snum}][episodes][{num}][players][link][]" value="{link}" placeholder="Ссылка" autocomplete="off" style="width: 65%;"><div class="pull-right"><a href="#" class="sp-player-remove"><i title="удалить" alt="удалить" class="fa fa-trash-o text-danger icon-trash"></i></a></div></div></li>';

$list = '<div class="sp-text-none"><a href="#" class="sp-season-add"><i class="fa fa-plus position-left"></i>Добавить сезон</a></div>';
$text_season = '<div class="sp-text-none"><a href="#" class="sp-series-add" data-snum="{num}"><i class="fa fa-plus position-left"></i>Добавить серию</a></div>';

if ( $mod == "editnews" ) {
  
  	$list_buffer = '';
	$episodes = [];
  	$seasonepisodesarr = ksep_read('episodes_'.$id);
  	if ( $seasonepisodesarr !== false ) {
      	$seasonepisodesarr = json_decode($seasonepisodesarr, true);
      	$szn = 1;
      	foreach ( $seasonepisodesarr as $season_num => $season ) {
          	$buffer = '';
          	if ( $season['episodes'] ) {
              	$epzd = 1;
              	foreach ( $season['episodes'] as $episode_num => $episode ) {
                  	$episodes[$szn][$epzd] = $episode;
                  
                  	$fields = get_fields_box('episode', $szn, $epzd, $episode['fields']);

					$episode['date'] = date('Y-m-d H:i:s', $episode['date']);

					$players = '';

					if ( !empty($episode['players']) ) {
						$episode['players'] = stripslashes($episode['players']);
						$episode['players'] = json_decode($episode['players'], true);

						foreach ($episode['players'] as $i => $player) {
							$players .= str_replace(['{snum}', '{num}', '{text}', '{link}'], [$szn, $epzd, $player['text'], $player['link']], $player_item);
						}
					}

					$buffer .= str_replace([
						'{snum}', '{num}', '{episode_num}', '{metatitle}', '{descr}', '{keywords}', '{fields}', '{players}', '{date}', '{approve}'
					], [
						$szn, $epzd, $episode['episode_num'], stripslashes($episode['metatitle']), stripslashes($episode['descr']), stripslashes($episode['keywords']), $fields, $players, $episode['date'], $episode['approve'] ? ' checked' : ''
					], $episode_item);
                  
                  	$epzd++;
                }
            }
          
          	if ( empty($buffer) ) {
				$buffer = $text_season;
			}
          
          	$fields = get_fields_box('season', $szn, false, $season['fields']);

			$season['date'] = date('Y-m-d H:i:s', $season['date']);

			$list_buffer .= str_replace([
				'{num}', '{season_num}', '{metatitle}', '{descr}', '{keywords}', '{list}', '{fields}', '{date}', '{approve}', '{shorturl}'
			], [
				$szn, $season['season_num'], stripslashes($season['metatitle']), stripslashes($season['descr']), stripslashes($season['keywords']), $buffer, $fields, $season['date'], $season['approve'] ? ' checked' : '', $season['shorturl'] ? ' checked' : ''
			], $season_item);

			$list_buffer = preg_replace("'\\[first\\](.*?)\\[/first\\]'is", (count($seasonepisodesarr) == 1) ? '\\1' : '', $list_buffer);
          	$szn++;
        }
    }
  	else $seasonepisodesarr = [];

	if ( !empty($list_buffer) ) {
		$list = $list_buffer;
	}
}

$start_box = str_replace('{list}', $list, $start_box);

$season_item = str_replace('{list}', $text_season, $season_item);

$season_item = str_replace(['{metatitle}', '{season_num}', '{descr}', '{keywords}', '{date}'], '', $season_item);
$episode_item = str_replace(['{metatitle}', '{episode_num}', '{descr}', '{keywords}', '{date}', '{players}'], '', $episode_item);
$season_item = str_replace('{approve}', ' checked', $season_item);
$season_item = str_replace('{shorturl}', ' ', $season_item);
$episode_item = str_replace('{approve}', ' checked', $episode_item);

$player_item = str_replace(['{text}', '{link}'], '', $player_item);

$season_item = str_replace('{fields}', get_fields_box('season'), $season_item);
$episode_item = str_replace('{fields}', get_fields_box('episode'), $episode_item);

echo <<<HTML
<script type="text/javascript">
$(document).ready(function() {
	let panel = $('.panel');
	let tpl = {
		start_box: `{$start_box}`,
		season_item: `{$season_item}`,
		episode_item: `{$episode_item}`,
		player_item: `{$player_item}`
	};
	var is_action = false;

	panel.find('.nav').append('<li id="tab-seriespages"><a href="#seriespages" data-toggle="tab" class="legitRipple"><i class="fa fa-bars icon-list position-left"></i> Сезоны/серии</a></li>');
	panel.find('.tab-content > .tab-pane:last').after('<div class="tab-pane" id="seriespages"><div style="padding: 25px;text-align: center;">Что-то пошло не так :(</div></div>');

	$(document).on('click', '.nav-tabs > li:not(.active)', function() {
		var form = $('#addnews');
		var series = $('#series');

		if ( $(this).is('#tab-seriespages') ) {
			if ( series.length === 0 ) {
				form.after('<form id="series" onsubmit="return false;"><div class="panel-body">' + tpl.start_box + '</div></form>');
				sp_uploader_start();

				$('.sp-seasons').find('> .sp-item:last > .sp-head > .sp-head-switch').trigger('click');
				$('.sp-seasons').find('> .sp-item:last .sp-series > .sp-item:last > .sp-head > .sp-head-switch').trigger('click');

				$(this).addClass('sp-loaded');

				series = $('#series');

				sp_uniform(series);

				sp_nestable(series);
			}

			form.hide();
			series.show();
		} else {
			if ( series.length > 0 ) {
				var data = series.serialize();
				var input = form.find('[name="series"]');

				if ( input.length === 0 ) {
					input = form.append('<input type="hidden" name="series">').find('[name="series"]');
				}

				input.val(data);

                if ( !data ) form.append('<input type="hidden" name="series_removed" value="1">');
			}

			form.show();
			series.hide();
		}
	});

	$(document).on('click', '.sp-head-switch', function(e) {
		if ( is_action ) {
			is_action = false;
			return false;
		}

		let item = $(this).closest('.sp-item');
		let btn = $(this).find('> .sp-head-switch');
		let flag = item.is('.sp-item-switched');

		item.parent().find('> .sp-item').removeClass('sp-item-switched');

		if ( !flag ) {
			item.addClass('sp-item-switched');
		}
	});

	$(document).on('click', '.sp-item-tabs > li', function(e) {
		let tabs = $(this).closest('.sp-item-tabs');
		let wrap = $(this).closest('.sp-item-tabs-wrap');

		wrap.find('> .sp-item-tabs-box').removeClass('visible');
		tabs.find('li').removeClass('active');

		$(this).addClass('active');
		wrap.find('> .sp-item-tabs-box').eq($(this).index()).addClass('visible');
	});

	$(document).on('click', '.sp-season-add', function(e) {
		$('.sp-seasons > .sp-text-none').remove();
		let html = tpl.season_item;
		let num = $('.sp-seasons > .sp-item').length+1;
		html = html.replace(/\{num\}/g, num);
		html = html.replace(/\[first\](.*?)\[\/first\]/gis, (num == 1) ? '\$1' : '');
		$('.sp-seasons').append(html);
		var season = $('.sp-seasons').find('> .sp-item:last');
		season.find('> .sp-head').trigger('click');

		sp_uniform(season);

		sp_uploader_start();

		return false;
	});

	$(document).on('click', '.sp-series-add', function(e) {
		is_action = true;

		let snum = $(this).data('snum');
		let season = $(this).closest('.sp-item');
		let series = season.find('.sp-series');

		season.find('> .sp-head').trigger('click');
		season.find('> .sp-item-content > .sp-item-tabs > li:eq(0)').trigger('click');

		series.find('> .sp-text-none').remove();
		series.append(tpl.episode_item.replace(/\{snum\}/g, snum).replace(/\{num\}/g, series.find('.sp-item').length+1));
		var episode = series.find('> .sp-item:last');
		episode.find('> .sp-head-item').trigger('click');

		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth()+1;
		var day = date.getDate();
		var hour = date.getHours();
		var min = date.getMinutes();
		var sec = date.getSeconds();

		episode.find('input[data-rel="calendar"]').val(year + '-' + (month > 9 ? month : '0' + month) + '-' + (day > 9 ? day : '0' + day) + ' ' + (hour > 9 ? hour : '0' + hour) + ':' + (min > 9 ? min : '0' + min) + ':' + (sec > 9 ? sec : '0' + sec));

		sp_uniform(episode);

		sp_nestable(episode);

		sp_uploader_start();

		return false;
	});

	$(document).on('click', '.sp-season-remove', function(e) {
		$(this).closest('.sp-item').remove();

		$('.sp-seasons > .sp-item:last-child > .sp-head').trigger('click');

		return false;
	});

	$(document).on('click', '.sp-episode-remove', function(e) {
		let episode = $(this).closest('.sp-item');
		let series = episode.closest('.sp-series');

		episode.remove();
		series.find('.sp-item:last-child > .sp-head-item').trigger('click');

		return false;
	});

	$(document).on('click', '.sp-player-add', function(e) {
		var season = $(this).data('season');
		var episode = $(this).data('episode');

		var list = $(this).closest('.sp-players').find('.dd-list');

		list.append(tpl.player_item.replace(/\\{snum\\}/g, season).replace(/\\{num\\}/g, episode));

		return false;
	});

	$(document).on('click', '.sp-player-remove', function(e) {
		$(this).closest('.dd-item').remove();
		return false;
	});

	var hash = location.hash.toString().replace(/[^0-9\-]/g, '').split('-');

	if ( $.isNumeric(hash[0]) ) {
		panel.find('.nav li').removeClass('active').parent().find('#tab-seriespages').trigger('click').addClass('active');

		var season = $('#sp-season-' + hash[0]).closest('.sp-item');
		var position = 0;

		if ( season.length ) {
			var position = season.offset().top;

			if ( !season.is('.sp-item-switched') ) {
				season.find('> .sp-head').trigger('click');
			}

			if ( $.isNumeric(hash[0]) ) {
				var episode = season.find('#sp-episode-' + hash[1]).closest('.sp-item');

				if ( episode.length ) {
					var position = episode.offset().top;

					if ( !episode.is('.sp-item-switched') ) {
						episode.find('> .sp-head').trigger('click');
					}
				}
			}
		}

		if ( position )  $($.browser.safari ? 'body' : 'html').animate({ scrollTop: position }, 1100);
	}
});

function sp_nestable(o) {
	o.find('.dd').nestable({
		maxDepth: 1,
		listClass: 'dd-list sp-players-list'
	});

	o.find('.dd-handle a').on('mousedown', function(e){
		e.stopPropagation();
	});

	o.find('.dd-handle a').on('touchstart', function(e){
		e.stopPropagation();
	});
}

function sp_uniform(o) {
    if (typeof setLocale !== 'undefined') jQuery.datetimepicker.setLocale(cal_language);
	o.find('[data-rel=calendar]').datetimepicker({
		format:'Y-m-d H:i:s',
	    step: 30,
	    closeOnDateSelect:true,
	    dayOfWeekStart: 1,
	    scrollMonth:false,
	    scrollInput:false
	});

	o.find('.icheck').uniform({
	    radioClass: 'choice',
	    wrapperClass: 'border-teal-600 text-teal-800',
	    fileDefaultHtml: filedefaulttext,
	    fileButtonHtml: filebtntext,
	    fileButtonClass: 'btn bg-teal btn-sm btn-raised'
	});
}

function sp_uploader_start() {
	$('input[type=hidden][class=sp_uploader_start]').each(function() {
		sp_uploader($(this).removeClass('sp_uploader_start').attr('id'));
	});
}

function spimagedelete(fid, value){
	DLEconfirm( 'Вы действительно хотите удалить изображение?', 'Информация', function () {
		ShowLoading('');

		$.post('engine/ajax/controller.php?mod=upload', { subaction: 'deluploads', user_hash: dle_login_hash, news_id: '{$news_id}', author: '{$author}', 'images[]' : value }, function(data){
			HideLoading('');

			$('#sp_uploadedfile_' + fid).html('');
			$('#' + fid).val('');
			$('#' + fid + '_upload .qq-upload-button, #' + fid + '_upload .qq-upload-button input').removeAttr('disabled');
		});
	});

	return false;
};

function sp_image_box(fid, value, thumb = false) {
	if ( value == '' ) return '';

	let path = value.indexOf('/posts/') != -1 ? value.toString().split('/posts/')[1] : value;
	let name = path.toString().split('/')[1].replace(/^[0-9]{10}_/, '');

	if ( thumb ) path = path.replace('/', '/thumbs/');
	var temp = path.split('|');
	path = temp[0];

	return "<div id=\"" + fid + "_image\" class=\"uploadedfile\" data-id=\"" + path + "\" data-alt=\"" + "\"><div class=\"info\">" + name + "</div><div class=\"uploadimage\"><img style=\"width:auto;height:auto;max-width:100px;max-height:90px;\" src=\"/uploads/posts/" + path +  "\" /></div><div class=\"info\"><!--<a href=\"#\" onclick=\"xfaddalt(\\'" + fid + "\\', \\'" + path + "\\');return false;\">{$lang['xf_img_descr']}</a><br>--><a href=\"#\" onclick=\"spimagedelete(\\'" + fid + "\\', \\'" + path + "\\');return false;\">{$lang['xfield_xfid']}</a></div></div>";
}

function sp_uploader(fid) {
	let field = $('#' + fid);
	let value = field.val();
	var up_image = sp_image_box(fid, value, field.data('thumb') == '1');
	let make_watermark = field.data('make_watermark') ? 1 : 0;
	let make_thumb = field.data('make_thumb') ? 1 : 0;
	let thumb_size = field.data('thumb_size');
	let thumb_seite = field.data('thumb_seite');
	let max_size = field.data('max_size');
	let ksep_type = field.data('ksep_type');
	let ksep_name = field.data('ksep_name');

	new qq.FileUploader({
		element: document.getElementById(fid + '_upload'),
		action: 'engine/ajax/controller.php?mod=upload',
		maxConnections: 1,
		multiple: false,
		allowdrop: false,
		encoding: 'multipart',
	    sizeLimit: 0,
		allowedExtensions: ['gif', 'jpg', 'jpeg', 'png', 'webp'],
	    params: {subaction: "upload", news_id: "{$news_id}", area: "xfieldsimage", submode: "ksep", author: "{$author}", user_hash: dle_login_hash, ksep_type: ksep_type, ksep_name: ksep_name},
	    template: '<div class="qq-uploader">' +
	            '<div id="sp_uploadedfile_' + fid + '" style="min-height: 2px;">' + up_image + '</div>' +
	            '<div class="qq-upload-button btn btn-green bg-teal btn-sm btn-raised" style="width: auto;">{$lang['xfield_xfim']}</div>' +
	            '<ul class="qq-upload-list" style="display:none;"></ul>' +
	         '</div>',
		onSubmit: function(id, fileName) {
			$('<div id="sp_uploadfile-'+id+'" class="file-box"><span class="qq-upload-file-status">{$lang['media_upload_st6']}</span><span class="qq-upload-file">&nbsp;'+fileName+'</span>&nbsp;<span class="qq-status"><span class="qq-upload-spinner"></span><span class="qq-upload-size"></span></span><div class="progress "><div class="progress-bar progress-blue" style="width: 0%"><span>0%</span></div></div></div>').appendTo('#' + fid + '_upload');
	    },
		onProgress: function(id, fileName, loaded, total){
			$('#sp_uploadfile-'+id+' .qq-upload-size').text(DLEformatSize(loaded)+' {$lang['media_upload_st8']} '+DLEformatSize(total));
			var proc = Math.round(loaded / total * 100);
			$('#sp_uploadfile-'+id+' .progress-bar').css( "width", proc + '%' );
			$('#sp_uploadfile-'+id+' .qq-upload-spinner').css( "display", "inline-block");
		},
		onComplete: function(id, fileName, response){
			if ( response.success ) {
				var image = response.flink ? response.flink : response.link;
				var thumb = response.flink ? true : false;

				var returnval = image.split('/posts/')[1];

				$('#sp_uploadfile-'+id+' .qq-status').html('{$lang['media_upload_st9']}');
				$('#sp_uploadedfile_' + fid).html( sp_image_box(fid, returnval, thumb) );
				$('#' + fid).val(returnval);

				$('#' + fid + '_upload .qq-upload-button, #' + fid + '_upload .qq-upload-button input').attr("disabled","disabled");

				setTimeout(function() {
					$('#sp_uploadfile-'+id).fadeOut('slow', function() { $(this).remove(); });
				}, 1000);

			} else {
				$('#sp_uploadfile-'+id+' .qq-status').html('{$lang['media_upload_st10']}');

				if( response.error ) $('#sp_uploadfile-'+id+' .qq-status').append( '<br /><span class="text-danger">' + response.error + '</span>' );

				setTimeout(function() {
					$('#sp_uploadfile-'+id).fadeOut('slow');
				}, 4000);
			}
		},
	    messages: {
	        typeError: "{$lang['media_upload_st11']}",
	        sizeError: "{$lang['media_upload_st12']}",
	        emptyError: "{$lang['media_upload_st13']}"
	    },
		debug: false
	});

	if ( value != "" ) {
		$('#'  + fid + '_upload .qq-upload-button, #'  + fid + '_upload .qq-upload-button input').attr("disabled","disabled");
	}
}
</script>

<style type="text/css">
.sp-head {

}

.sp-head-main {
	padding: 0px 0px 15px 0px;
}

.sp-head-item {
	cursor: pointer;
	padding: 10px 15px;
}

.sp-head-series {
	border-top: 1px solid #eaeaea;
	padding: 15px 0px 5px 0px;
	margin: 0px 0px 10px 0px;
}

.sp-head-switch {
    display: inline-block;
    font-weight: bold;
    margin: 0px 5px 0px 0px;
}

.sp-head-switch:after {
	content: '+';
	display: inline-block;
    font-size: 20px;
}

.sp-item-switched > .sp-head >.sp-head-switch:after {
	content: '-';
	padding: 0px 0px 0px 3px;
    font-size: 20px;
}

.sp-head-title {
	font-weight: bold;
	display: inline-block;
}

.sp-head-actions {
	float: right;
}

.sp-item {
    border-top: 1px solid #eaeaea;
    border-left: 1px solid #eaeaea;
    border-right: 1px solid #eaeaea;
}

.sp-item:last-child {
	border-bottom: 1px solid #eaeaea;
	margin: 0px !important;
}

.sp-item-content {
	display: none;
	border-top: 1px solid #eaeaea;
	padding: 15px 15px;
}

.sp-item.sp-item-switched > .sp-head {
	background: #f6f6f6;
}

.sp-item.sp-item-switched > .sp-item-content {
	display: block !important;
}

.sp-item-field {
	display: flex;
	padding: 10px 0px;
	border-bottom: 1px solid #eaeaea;
}

.sp-item-field:last-child {
	border-bottom: 0px;
}

.sp-item-field label {
	width: 200px;
	padding: 6px 0px 0px 0px;
}

.sp-item-field input[type="text"] {

}

.sp-item-field-checkbox label {
	width: auto !important;
}

.sp-item-series {

}

.sp-text-none {

}

.sp-item-fields {

}

.sp-item-tabs {
	list-style: none;
	margin: 0px;
	padding: 0px;
}

.sp-item-tabs li {
	text-transform: uppercase;
	font-size: 11px;
	display: inline-block;
	margin: 0px 10px 0px 0px;
	cursor: pointer;
	color: #8a8a8a;
}

.sp-item-tabs li.active {
	font-weight: bold;
	color: #000;
}

.sp-item-tabs li:hover {
	color: #000;
}

.sp-item-tabs-box {
	display: none;
}

.sp-item-tabs-box.visible {
	display: block !important;
}

.sp-series {
	padding: 12px 0px 0px 0px;
}

.sp-season-remove, .sp-episode-remove {
	display: none;
}

.sp-item:last-child > .sp-head .sp-season-remove, .sp-item:last-child > .sp-head .sp-episode-remove {
	display: inline-block;
}

.sp-players .dd {
	margin-bottom: 10px;
}

.sp-players-list {
	max-width: 100% !important;
}

.sp-players-list .dd-item input {
	border: 0px;
	font-weight: bold;
	width: 25%;
	font-size: 12px;
}
</style>
HTML;

?>
