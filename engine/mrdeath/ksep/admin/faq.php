<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$breadcrumbs = ['?mod=' . $mod => $descr, '' => 'Описание тегов'];

$cron_key = isset($series_options['cron_key']) ? $series_options['cron_key'] : 'ваш_ключ_для_запуска_крон';

$content = <<<HTML
<style>
code {
    display: unset;
}
</style>
    <div class="panel panel-flat">
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">Ссылка для запуска через крон</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
                            <code>{$config['http_home_url']}engine/ajax/controller.php?mod=kodik_ajax_controller&file=cron&action=generate&key={$cron_key}</code> - если вы настроили в разделе управления доп. полями серий загрузку кадра/кадров серии на сервер, то добавьте задачу в ваш крон на открытие данной ссылки один раз в минуту.
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">Правила rewrite для Nginx</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
<textarea style="width:100%;height:230px;" disabled="">rewrite "^/([^.]+)/([0-9]+)-(.*)/season-([^.]+)/episode-([^.]+).html$" /index.php?newsid=$2&seourl=$3&seocat=$1&szn=$4&epzd=$5 last;
rewrite "^/([^.]+)/([0-9]+)-(.*)/season-([^.]+).html$" /index.php?newsid=$2&seourl=$3&seocat=$1&szn=$4 last;
rewrite "^/([^.]+)/([0-9]+)-(.*)/episode-([^.]+).html$" /index.php?newsid=$2&seourl=$3&seocat=$1&epzd=$4 last;

rewrite "^/([0-9]+)-(.*)/season-([^.]+)/episode-([^.]+).html$" /index.php?newsid=$1&seourl=$2&szn=$3&epzd=$4 last;
rewrite "^/([0-9]+)-(.*)/season-([^.]+).html$" /index.php?newsid=$1&seourl=$2&szn=$3 last;
rewrite "^/([0-9]+)-(.*)/episode-([^.]+).html$" /index.php?newsid=$1&seourl=$2&epzd=$3 last;

rewrite "^/seasons_pages.xml$" /uploads/seasons_pages.xml last;
rewrite "^/episodes_pages.xml$" /uploads/episodes_pages.xml last;</textarea>
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">Правила rewrite для Apache</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
<textarea style="width:100%;height:230px;" disabled="">RewriteRule ^([^.]+)/([0-9]+)-(.*)/season-([^.]+)/episode-([^.]+).html$ index.php?newsid=$2&seourl=$3&seocat=$1&szn=$4&epzd=$5 [L]
RewriteRule ^([^.]+)/([0-9]+)-(.*)/season-([^.]+).html$ index.php?newsid=$2&seourl=$3&seocat=$1&szn=$4 [L]
RewriteRule ^([^.]+)/([0-9]+)-(.*)/episode-([^.]+).html$ index.php?newsid=$2&seourl=$3&seocat=$1&epzd=$4 [L]

RewriteRule ^([0-9]+)-(.*)/season-([^.]+)/episode-([^.]+).html$ index.php?newsid=$1&seourl=$2&szn=$3&epzd=$4 [L]
RewriteRule ^([0-9]+)-(.*)/season-([^.]+).html$ index.php?newsid=$1&seourl=$2&szn=$3 [L]
RewriteRule ^([0-9]+)-(.*)/episode-([^.]+).html$ index.php?newsid=$1&seourl=$2&epzd=$3 [L]

RewriteRule ^seasons_pages.xml$ uploads/seasons_pages.xml [L]
RewriteRule ^episodes_pages.xml$ uploads/episodes_pages.xml [L]</textarea>
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">Стили и скрипты</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
                            <b>Если вы используете в модуле Advanced Kodik Parser плейлист, т.е. Модули>Вывод плеера>Выводить плеер модулем? - активировано то тогда пропустите данный раздел, добавлять скрипты и стили вам не нужно.</b>
                            <br>Если же не используете то в подключённый к шаблону файл стилей css добавьте такие стили:
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
<textarea style="width:100%;height:230px;" disabled="">/* Kodik Playlist
----------------------------------------------- */
#lesc{overflow:hidden;padding:0 0 0 3px;cursor:pointer;width:13px;height:13px;top:2px;position:relative;border:none;margin: 0 !important}
#player{background:#000;min-height:460px}
.b-player{background:#000;padding-top:10px;position:relative}
.b-player iframe{overflow:hidden;width: 100% !important}
.b-player iframe::-webkit-scrollbar{display:block}
.b-player__restricted{background:#000;height:360px;position:relative}
.b-player__restricted_string{color:#fff;display:block;font-size:14px;font-weight:bold;margin-top:-30px;position:absolute;top:50%;left:0;text-align:center;text-transform:uppercase;width:100%}
.b-player__restricted_string a{text-decoration:underline}
.b-post__lastepisodeout{background:#ccc no-repeat 13px 50%;overflow:hidden;padding: 10px 7px 10px 5px;}
.b-post__lastepisodeout h2{color:#222;font-size: 14px !important;font-weight:bold;line-height:16px;margin:0 0 0 0}
#player-loader-overlay{background:#000 url(../images/ajax-loader-big-black.gif) no-repeat 50% 50%;display:none;height: calc(100% - 50px);position:absolute;width:100%}
.b-translators__block{background:#151515;padding-top:10px;padding-left:10px;padding-bottom:10px;text-align:left !important}
.b-translators__title{color:#fff;font-size:15px;font-weight:bold}
.b-translators__title h2,.b-translators__title h3{display:inline}
.b-translators__list{overflow:hidden;-moz-padding-start:0px;padding-start:0px;-webkit-padding-start:0px;margin:0 0 0 0}
.b-translator__item{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background:#2d2d2d;color:#fff;cursor:pointer;float:left;font-size:14px;margin-left: 3px !important;margin-top:3px;overflow:hidden;padding:5px 0 5px 10px;text-overflow:ellipsis;white-space:nowrap;width:32.5%}
.b-translator__item.single{float:none;display:inline-block;margin-left:0;padding:5px 10px;width:auto}
.b-translator__item:nth-child(3n +1){margin-left:0px}
.b-translator__item.active,.b-simple_season__item.active,.b-simple_episode__item.active{background:#5d5d5d !important;cursor:default}
.b-translator__item:hover,.b-changeplayer__list li:hover,.b-simple_season__item:hover,.b-simple_episode__item:hover{background:#4d4d4d}
.b-changeplayer__list{overflow:hidden}
.b-changeplayer__list li{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background:#2d2d2d;color:#fff;cursor:pointer;float:left;font-size:14px;margin-top:5px;overflow:hidden;padding:5px 10px}
.b-changeplayer__list li.active{background:#000 !important;cursor:default}
.b-episodes__wrapper{border-bottom:1px solid #dcdcdc;margin-bottom:18px;-webkit-transition:opacity 0.5s ease;-moz-transition:opacity 0.5s ease;-o-transition:opacity 0.5s ease;transition:opacity 0.5s ease}
.b-episodes__list{margin-left:-16px;padding:32px 0 15px;-webkit-transition:opacity 0.5s ease;-moz-transition:opacity 0.5s ease;-o-transition:opacity 0.5s ease;transition:opacity 0.5s ease}
.b-episodes__list li{float:left;margin:0 0 17px 17px}
.b-episodes__list .resume-main img{background-position:50% 0;background-size:166px}
.b-episodes__list .string{z-index:1}
.b-episodes__list .string span{background:#000;padding:2px 4px}
.b-episodes__list .play{z-index:2}
.b-simple_seasons__list{margin:0 auto;padding:10px;padding:4px 10px 12px 13px;list-style:none}
.b-simple_seasons__title{color:#fff;font-size:15px;font-weight:bold;padding-top:10px;padding-left:11px;text-align:left}
.b-simple_season__item{background:#2d2d2d;color:#fff;cursor:pointer;float:left;font-size:13px;margin: 0 2px 2px 0 !important;min-width:11.8%;padding:5px 7px;text-align:left;list-style: none !important}
.b-simple_episodes__list{margin:0 auto;padding:10px 10px 12px 13px;text-align:left}
.b-simple_episode__item{background:#2d2d2d;color:#fff;cursor:pointer;display:inline-block;font-size:13px;margin: 0 2px 2px 0 !important;width:69px;padding:5px 7px;text-align:left}
#kodik_player_ajax{min-height:600px;background: #151515;}
#simple-episodes-tabs{white-space:nowrap;overflow:hidden;margin:0 30px 0 30px;display:block;position:relative}
@media only screen and (max-width: 590px) {
    #simple-episodes-tabs {-webkit-overflow-scrolling: touch;overflow-x:scroll}
    #kodik_player_ajax{min-height:300px}
    #player{background:#000;min-height:300px}
}
.b-simple_episodes__list{-moz-padding-start:0px;padding-start:0px;-webkit-padding-start:0px;text-align:left}
.b-simple_episode__item{display:inline-block;font-size:11px;background:#242424;color:#F0F0F0;cursor:pointer;padding:5px;margin:0 3px;-webkit-transition:background .3s ease;-moz-transition:-moz-background .3s ease;-o-transition:-o-background .3s ease;transition:background .3s ease;text-align:left}
.b-simple_episode__item.active{background:#525252 !important;cursor:default}
.prenext{position:relative}
.prevpl,.nextpl{position:absolute;top:0px;font-size:xx-large;width:25px;height:20px;color:#D5D9D9;cursor:pointer;font-weight:bold;z-index:999}
.prevpl{left:0px}
.nextpl{right:0px}
#player_wrap.premact{width:100%}
.show-flex-grid{display: flex;flex-wrap: wrap;padding: 5px;max-height: 155px;overflow-y: auto;}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            В подключённый к шаблону файл скриптов js добавьте такие скрипты:
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
<textarea style="width:100%;height:230px;" disabled="">function kodik_translates_alt() {
    $('#translators-list').on('click','.b-translator__item',function() {
        var _self = $(this);
        if(!_self.hasClass('active')) {
            
            $('.b-translator__item').removeClass('active');
            _self.addClass('active');
            
            var this_link = _self.attr("data-this_link");
            $('#player_kodik').html('<iframe src="'+this_link+'" width="724" height="460" frameborder="0" allowfullscreen=""></iframe>');

        }
    });
}</textarea>
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">{THEME}/kodik_episodes/seasons_links.tpl - оформление вывода ссылок на сезоны</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
					<tr>
                        <td style="width:100%">
                            Базовый шаблон для вывода данных:
                        </td>
                    </tr>
					<tr>
                        <td style="width:100%">
							<textarea style="width:100%;height:130px;" disabled=""><a href="{season-link}" style="
		display: inline-flex;width: 50px;height: 30px;
		border: 1px solid #000;border-radius: 12px;color: #000;
		margin: 5px;align-items: center;justify-content: center;
		text-align: center;
">{season-num}</a></textarea>
						</td>
					</tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-link}</code> - выводит ссылку на сезон
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-num}</code> - выводит номер сезона
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[season-active]...[/season-active]</code> - содержимое тега будет выводено на странице сезона, при помощи его можно стилизировать активный сезон в списке ссылок на сезоны
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">{THEME}/kodik_episodes/episodes_links.tpl - оформление вывода ссылок на серии</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
					<tr>
                        <td style="width:100%">
                            Базовый шаблон для вывода данных:
                        </td>
                    </tr>
					<tr>
                        <td style="width:100%">
							<textarea style="width:100%;height:130px;" disabled=""><a href="{episode-link}" style="
		display: inline-flex;width: 50px;height: 30px;
		border: 1px solid #000;border-radius: 12px;color: #000;
		margin: 5px;align-items: center;justify-content: center;
		text-align: center;
">{episode-num}</a></textarea>
						</td>
					</tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-link}</code> - выводит ссылку на серию
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-num}</code> - выводит номер серии
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episode-active]...[/episode-active]</code> - содержимое тега будет выводено на странице серии, при помощи его можно стилизировать активную серию в списке ссылок на серии
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">{THEME}/fullstory.tpl - теги работающие в полной новости</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
                            <code>[seasons-links]...[/seasons-links]</code> - выводит тег в случае если есть ссылки на сезоны. Если сезон всего один и в нём выставлена галочка "Короткий URL серий(без сезона)", то содержимое тега будет вырезано
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-seasons-links]...[/not-seasons-links]</code> - выводит тег в случае если нету ссылки на сезоны.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{seasons-links}</code> - выводит ссылки на сезоны, оформленные при помощи файла seasons_links.tpl
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episodes-links]...[/episodes-links]</code> - выводит тег в случае если есть ссылки на серии, сезон всего один и в нём выставлена галочка "Короткий URL серий(без сезона)". Если сезонов несколько, то содержимое тега будет вырезано
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episodes-links]...[/not-episodes-links]</code> - выводит тег в случае если нету ссылки на серии.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episodes-links}</code> - выводит ссылки на серии, оформленные при помощи файла episodes_links.tpl
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">{THEME}/kodik_episodes/season_page.tpl - теги работающие на странице сезона</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
					<tr>
                        <td style="width:100%">
                            Базовый шаблон для вывода данных:
                        </td>
                    </tr>
					<tr>
                        <td style="width:100%">
							<textarea style="width:100%;height:230px;" disabled=""><div class="episode-page">
	[seasons-links]<div class="episode-seasons">{seasons-links}</div>[/seasons-links]
	<div class="episode-playlist">{kodik_playlist}</div>
	[episodes-links]<div class="episode-links">{episodes-links}</div>[/episodes-links]
	<div class="episode-story">{full-story}</div>
</div>
<style>
.episode-page {
    display: flex;
    flex-direction: column;
    margin: 30px 0;
    padding: 30px 0;
}

.episode-links, .episode-seasons {
    display: flex;
    flex-wrap: wrap;
    margin: 10px;
}

.episode-links a {
    display: flex!important;
}
</style></textarea>
						</td>
					</tr>
                    <tr>
                        <td style="width:100%">
                            <b>В данном файле доступны абсолютно все теги полной новости!</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[seasons-links]...[/seasons-links]</code> - выводит тег в случае если есть ссылки на сезоны. Если сезон всего один и в нём выставлена галочка "Короткий URL серий(без сезона)", то содержимое тега будет вырезано
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-seasons-links]...[/not-seasons-links]</code> - выводит тег в случае если нету ссылки на сезоны.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{seasons-links}</code> - выводит ссылки на сезоны, оформленные при помощи файла seasons_links.tpl
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episodes-links]...[/episodes-links]</code> - выводит тег в случае если есть ссылки на серии текущего сезона
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episodes-links]...[/not-episodes-links]</code> - выводит тег в случае если нету ссылки на серии.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episodes-links}</code> - выводит ссылки на серии текущего сезона, оформленные при помощи файла episodes_links.tpl
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[season-prev]...[/season-prev]</code> - выводит содержимое тега если есть предыдущий сезон
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-prev}</code> - выводит ссылку на предыдущий сезон
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-season-prev]...[/not-season-prev]</code> - выводит содержимое тега если предыдущего сезона нет
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[season-next]...[/season-next]</code> - выводит содержимое тега если есть следующий сезон
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-next}</code> - выводит ссылку на следующий сезон
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-season-next]...[/not-season-next]</code> - выводит содержимое тега если следующего сезона нет
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-date}</code> - дата добавления сезона в формате согласно настройкам системы
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-date=формат даты}</code> - выводит дату добавления сезона в заданном в теге формате. Тем самым вы можете выводить не только дату целиком но и ее отдельные части. Формат даты задается задается согласно формату принятому в PHP. Например тег {season-date=d} выведет день месяца публикации сезона, а тег {season-date=F} выведет название месяца, а тег {season-date=d-m-Y H:i} выведет полную дату и время
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-num}</code> - номер сезона
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[season-field-X]...[/season-field-X]</code> - выводит содержимое тега если доп. поле сезона с именем X не пустое
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-field-X}</code> - выводит содержимое доп. поле сезона с именем X, если поле пустое то не выведет ничего
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-field-x_image_url}</code> - выводит чистую ссылку на картинку загруженную в доп. поле X. Данный тег работает с полями типа "Загружаемое изображение"
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{season-field-x_thumb_url}</code> - выводит чистую ссылку на уменьшенную картинку (тумб) загруженную в доп. поле X. Данный тег работает с полями типа "Загружаемое изображение"
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-season-field-X]...[/not-season-field-X]</code> - выводит содержимое тега если доп. поле сезона с именем X пустое
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
        <div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">{THEME}/kodik_episodes/episode_page.tpl - теги работающие на странице серии</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
					<tr>
                        <td style="width:100%">
                            Базовый шаблон для вывода данных:
                        </td>
                    </tr>
					<tr>
                        <td style="width:100%">
							<textarea style="width:100%;height:230px;" disabled=""><div class="episode-page">
	[seasons-links]<div class="episode-seasons">{seasons-links}</div>[/seasons-links]
	[episode-player]<div class="episode-playlist">{episode-player}</div>[/episode-player]
	[episodes-links]<div class="episode-links">{episodes-links}</div>[/episodes-links]
	<div class="episode-story">{full-story}</div>
</div>
<style>
.episode-page {
    display: flex;
    flex-direction: column;
    margin: 30px 0;
    padding: 30px 0;
}

.episode-links, .episode-seasons {
    display: flex;
    flex-wrap: wrap;
    margin: 10px;
}

.episode-links a {
    display: flex!important;
}
</style></textarea>
						</td>
					</tr>
                    <tr>
                        <td style="width:100%">
                            <b>В данном файле доступны абсолютно все теги полной новости, а так же все теги доступные в файле season_page.tpl!</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episode-prev]...[/episode-prev]</code> - выводит содержимое тега если есть предыдущая серия
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-prev}</code> - выводит ссылку на предыдущую серию
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episode-prev]...[/not-episode-prev]</code> - выводит содержимое тега если предыдущей серии нет
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episode-next]...[/episode-next]</code> - выводит содержимое тега если есть следующая серия
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-next}</code> - выводит ссылку на следующую серию
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episode-next]...[/not-episode-next]</code> - выводит содержимое тега если следующей серии нет
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-date}</code> - дата добавления серии в формате согласно настройкам системы
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-date=формат даты}</code> - выводит дату добавления серии в заданном в теге формате. Тем самым вы можете выводить не только дату целиком но и ее отдельные части. Формат даты задается задается согласно формату принятому в PHP. Например тег {episode-date=d} выведет день месяца публикации серии, а тег {episode-date=F} выведет название месяца, а тег {episode=d-m-Y H:i} выведет полную дату и время
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-num}</code> - номер серии
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episode-field-X]...[/episode-field-X]</code> - выводит содержимое тега если доп. поле серии с именем X не пустое
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-field-X}</code> - выводит содержимое доп. поле серии с именем X, если поле пустое то не выведет ничего
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-field-x_image_url}</code> - выводит чистую ссылку на картинку загруженную в доп. поле X. Данный тег работает с полями типа "Загружаемое изображение"
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-field-x_thumb_url}</code> - выводит чистую ссылку на уменьшенную картинку (тумб) загруженную в доп. поле X. Данный тег работает с полями типа "Загружаемое изображение"
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episode-field-X]...[/not-episode-field-X]</code> - выводит содержимое тега если доп. поле серии с именем X пустое
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[episode-player]...[/episode-player]</code> - выводит содержимое тега если у серии есть ссылки на плеер и сформированный плейлист
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>{episode-player}</code> - выводит сформированный плейлист
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            <code>[not-episode-player]...[/not-episode-player]</code> - выводит содержимое тега если у серии нет ссылок на плеер
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
		<div class="panel-body" style="padding: 20px; font-size: 20px; font-weight: bold; display: block;">Как задать метатеги на страницах сезонов и серий:</div>
        <div class="table-responsive">
			<table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width:100%">
                            <b>Используйте описанные ниже теги в файлах season_page.tpl и episode_page.tpl, комбинируя их с общими тегами, описанными для каждого файла</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            В самом начале файла season_page.tpl и episode_page.tpl, в качестве первой строчки, вставьте такую конструкцию
<textarea style="width:100%;height:110px;" disabled="">
[kodik-metatags]
<title>Метатег title на странице сезона/серии</title>
<description>Метатег description на странице сезона/серии</description>
<keywords>Метатег keywords на странице сезона/серии</keywords>
[/kodik-metatags]
</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            Пример метатегов для страницы сезона
<textarea style="width:100%;height:110px;" disabled="">
[kodik-metatags]
<title>{title} {season-num} сезон в русской озвучке</title>
<description>Смотреть {title} {season-num} сезон в русской озвучке и с русскими субтитрами</description>
<keywords>{title}, {season-num} сезон, в русской озвучке, с русскими субтитрами</keywords>
[/kodik-metatags]
</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%">
                            Пример метатегов для страницы серии
<textarea style="width:100%;height:110px;" disabled="">
[kodik-metatags]
<title>{title} {episode-num} серия {season-num} сезона в русской озвучке</title>
<description>Смотреть {title} {episode-num} серию {season-num} сезона в русской озвучке и с русскими субтитрами</description>
<keywords>{title}, {episode-num} серия {season-num} сезона, в русской озвучке, с русскими субтитрами</keywords>
[/kodik-metatags]
</textarea>
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
		
	</div>
HTML;

?>