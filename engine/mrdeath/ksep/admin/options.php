<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$breadcrumbs = ['?mod=' . $mod => $descr, '' => 'Параметры'];

$options = '';
$main = '';
$season = '';
$aap = '';

$xfields_text = $xfields_img = $xfields_all = ['-'];
if ( isset($series_options['fields']['episode']) && $series_options['fields']['episode'] ) {
    foreach ( $series_options['fields']['episode'] as $ep_xfield ) {
        if ( $ep_xfield['3'] == 'image' ) $xfields_img[$ep_xfield['0']] = $ep_xfield['1'];
        else $xfields_text[$ep_xfield['0']] = $ep_xfield['1'];
        $xfields_all[$ep_xfield['0']] = $ep_xfield['1'];
    }
}



$options .= ShowItem('options[cron_key]', 'Секретный ключ для работы крона', 'Введите ключ-пароль для работы крона. Данный ключ вы будете использовать в качестве параметра key в крон-ссылках.<br>Можете скопировать такой ключ: <b>'.md5(time().$config['http_home_url'] . $_SESSION['user_id']['email']).'</b>', $series_options['cron_key'], 'text');
$options .= ShowItem('options[future]', 'Не выводить на сайте сезоны и серии, дата публикации которых, еще не наступила', 'При включении данной настройки сезоны и серии будут появляться на сайте по мере наступления времени их публикации.', $series_options['future'], 'checkbox');
$options .= ShowItem('options[require_players]', 'Не выводить на сайте серии без плееров', 'При включении данной настройки, на сайте НЕ будут выводится серии, у которых отсутствуют плеера.', $series_options['require_players'], 'checkbox');
$options .= ShowItem('options[parse_special]', 'Парсить спэшлы, спэцвыпуски?', 'При включении данной настройки, на сайте будут парсится ещё и 0 сезон с специальными сериями материала.', $series_options['parse_special'], 'checkbox');
$options .= ShowItem('options[only_one]', 'Выводить сразу серии в полной новости', 'Если у материала только 1 сезон, то будет выводиться сразу серии вместо ссылки сезона.', $series_options['only_one'], 'checkbox');
$options .= ShowItem('options[priority_season]', 'Приоритет сезонов в sitemap', 'Приоритет экспортируемых URL в карту сайта относительно других URL на Вашем сайте. Допустимый диапазон значений — от 0.0 до 1.0. Это значение не влияет на сравнение Ваших страниц со страницами на других сайтах.', $series_options['priority_season'], 'text');
$options .= ShowItem('options[priority_episode]', 'Приоритет серий в sitemap', 'Приоритет экспортируемых URL в карту сайта относительно других URL на Вашем сайте. Допустимый диапазон значений — от 0.0 до 1.0. Это значение не влияет на сравнение Ваших страниц со страницами на других сайтах.', $series_options['priority_episode'], 'text');

$main .= ShowItem('options[main][sort_seasons]', 'Порядок сортировки сезонов', 'Выберите порядок сортировки сезонов', $series_options['main']['sort_seasons'], 'select', ['По возрастанию', 'По убыванию']);
$main .= ShowItem('options[main][sort_episodes]', 'Порядок сортировки серий', 'Выберите порядок сортировки серий', $series_options['main']['sort_episodes'], 'select', ['По возрастанию', 'По убыванию']);

$season .= ShowItem('options[season][one_season]', 'Если сезон один, то всегда использовать короткий URL серий(без сезона в ссылке) ', 'При включении данной настройки, в сериалах у которых один сезон всегда будет выводиться короткая ссылка без сезона в ней.', $series_options['season']['one_season'], 'checkbox');
$season .= ShowItem('options[season][sort_seasons]', 'Порядок сортировки сезонов', 'Выберите порядок сортировки сезонов', $series_options['season']['sort_seasons'], 'select', ['По возрастанию', 'По убыванию']);
$season .= ShowItem('options[season][sort_episodes]', 'Порядок сортировки серий', 'Выберите порядок сортировки серий', $series_options['season']['sort_episodes'], 'select', ['По возрастанию', 'По убыванию']);

$aap .= ShowItem('options[aap][kadr1_img]', 'Доп. поле с первым кадром', 'Если вы хотите чтобы кадр загружался на ваш сервер, то выберите доп. поле с типом "Загружаемое изображение". Если вы не хотите загружать кадр на свой сервер, тогда выберите доп. поле с типом "Одна строка", в таком случае будет вставляться ссылка на кадр с сайта Kodik. Если оставить пустым, загружаться/заполняться не будет', $series_options['aap']['kadr1_img'], 'select', $xfields_all);
$aap .= ShowItem('options[aap][kadr2_img]', 'Доп. поле со вторым кадром', 'Если вы хотите чтобы кадр загружался на ваш сервер, то выберите доп. поле с типом "Загружаемое изображение". Если вы не хотите загружать кадр на свой сервер, тогда выберите доп. поле с типом "Одна строка", в таком случае будет вставляться ссылка на кадр с сайта Kodik. Если оставить пустым, загружаться/заполняться не будет', $series_options['aap']['kadr2_img'], 'select', $xfields_all);
$aap .= ShowItem('options[aap][kadr3_img]', 'Доп. поле с третим кадром', 'Если вы хотите чтобы кадр загружался на ваш сервер, то выберите доп. поле с типом "Загружаемое изображение". Если вы не хотите загружать кадр на свой сервер, тогда выберите доп. поле с типом "Одна строка", в таком случае будет вставляться ссылка на кадр с сайта Kodik. Если оставить пустым, загружаться/заполняться не будет', $series_options['aap']['kadr3_img'], 'select', $xfields_all);
$aap .= ShowItem('options[aap][kadr4_img]', 'Доп. поле с четвертым кадром', 'Если вы хотите чтобы кадр загружался на ваш сервер, то выберите доп. поле с типом "Загружаемое изображение". Если вы не хотите загружать кадр на свой сервер, тогда выберите доп. поле с типом "Одна строка", в таком случае будет вставляться ссылка на кадр с сайта Kodik. Если оставить пустым, загружаться/заполняться не будет', $series_options['aap']['kadr4_img'], 'select', $xfields_all);
$aap .= ShowItem('options[aap][kadr5_img]', 'Доп. поле с пятым кадром', 'Если вы хотите чтобы кадр загружался на ваш сервер, то выберите доп. поле с типом "Загружаемое изображение". Если вы не хотите загружать кадр на свой сервер, тогда выберите доп. поле с типом "Одна строка", в таком случае будет вставляться ссылка на кадр с сайта Kodik. Если оставить пустым, загружаться/заполняться не будет', $series_options['aap']['kadr5_img'], 'select', $xfields_all);
$aap .= ShowItem('options[aap][translations]', 'Доп. поле с доступными озвучками', 'Выберите доп. поле в которое будет записано доступные озвучки серии. Если оставить пустым, записывать не будет', $series_options['aap']['translations'], 'select', $xfields_text);
$aap .= ShowItem('options[aap][plus_episode]', 'Добавить одну или несколько пустых серий', 'Введите количество пустых серий, которые будут добавляться к сезону сериала. Для отключения выставьте 0 или оставьте пустым.<br><b>Внимание: в общих настройках чекбокс "Не выводить на сайте серии без плееров" должен быть отключён!</b>', $series_options['aap']['plus_episode'], 'text');

$content = <<<HTML
<form name="options" onsubmit="save_config();return false">
	<div class="panel panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs nav-tabs-solid">
				<li class="active"><a href="#options" data-toggle="tab" class="legitRipple"><i class="fa fa-home position-left"></i> Общие настройки</a></li>
				<li><a href="#aap" data-toggle="tab" class="legitRipple" aria-expanded="false"><i class="fa fa-plug position-left"></i> Интеграция с AAP</a></li>
			</ul>
		</div>

		<div class="panel-tab-content tab-content">
            <div class="tab-pane active" id="options">
                <div class="panel-body border-bottom">Общие настройки</div>
		    	<div class="table-responsive">
				    <table class="table table-striped options-list">
				        <tbody>
				            {$options}
				        </tbody>
				    </table>
			    </div>
			    <div class="panel-body border-bottom" style="border-top: 1px solid #ddd;">Страница новости</div>
		    	<div class="table-responsive">
				    <table class="table table-striped options-list">
				        <tbody>
				            {$main}
				        </tbody>
				    </table>
			    </div>
			    <div class="panel-body border-bottom" style="border-top: 1px solid #ddd;">Страница сезона</div>
		    	<div class="table-responsive">
				    <table class="table table-striped options-list">
				        <tbody>
				            {$season}
				        </tbody>
				    </table>
			    </div>
		    </div>

		    <div class="tab-pane" id="aap">
		    	<div class="table-responsive">
				    <table class="table table-striped options-list">
				        <tbody>
				            {$aap}
				        </tbody>
				    </table>
			    </div>
		    </div>

		</div>

		<div class="panel-footer">
			<div class="pull-left">
				<button type="submit" class="btn bg-primary-600 btn-sm btn-raised position-left legitRipple">Сохранить настройки</button>
				<button type="button" class="btn bg-slate-600 btn-sm btn-raised position-left legitRipple" onclick="location.href = '{$PHP_SELF}?mod={$mod}';return false">Вернуться назад</button>
			</div>
		</div>
	</div>

	<input type="hidden" name="user_hash" value="{$dle_login_hash}">
	<input type="hidden" name="mod" value="{$mod}">
	<input type="hidden" name="action" value="ajax">
	<input type="hidden" name="subaction" value="save_options">
</form>

<script type="text/javascript">
function save_config() {
	$.post('{$PHP_SELF}', $('form[name=options]').serialize(), function(data){
		data = JSON.parse(data);

		if ( data.success === true ) {
			Growl.info({
				title: 'Информация',
				text: 'Настройки успешно сохранены'
			});
		} else {
			Growl.error({
				title: 'Информация',
				text: data.message
			});
		}
	});

	return false;
}
</script>
HTML;

?>
