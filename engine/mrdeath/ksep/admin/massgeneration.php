<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$breadcrumbs = ['?mod=' . $mod => $descr, '' => 'Массовая генерация'];

$content = <<<HTML
<style>
.update-status{padding:10px;border-top:1px solid #ddd;position:relative}
.update-status__current{position:absolute;width:100%;top:21px;font-weight:700;text-align:center;z-index:10}
.update-status__msg{background:#89d6e2;padding:10px;-webkit-border-radius:4px;-moz-border-radius:4px;-ms-border-radius:4px;-o-border-radius:4px;border-radius:4px;    max-height: 500px;overflow-y: auto;}
.progress{height:20px;-webkit-border-radius:4px;-moz-border-radius:4px;-ms-border-radius:4px;-o-border-radius:4px;border-radius:4px;margin-bottom:10px}
.progress .bar{float:left;width:0;height:100%;font-size:12px;color:#fff;text-align:center;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#0e90d2;background-image:-moz-linear-gradient(top,#149bdf,#0480be);background-image:-webkit-gradient(linear,0 0,0 100%,from(#149bdf),to(#0480be));background-image:-webkit-linear-gradient(top,#149bdf,#0480be);background-image:-o-linear-gradient(top,#149bdf,#0480be);background-image:linear-gradient(to bottom,#149bdf,#0480be);background-repeat:repeat-x;filter:progid:dximagetransform.microsoft.gradient(startColorstr='#ff149bdf',endColorstr='#ff0480be',GradientType=0);-webkit-box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);-moz-box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-transition:width .6s ease;-moz-transition:width .6s ease;-o-transition:width .6s ease;transition:width .6s ease}
.progress-success .bar,.progress .bar-success{background-color:#5eb95e;background-image:-moz-linear-gradient(top,#62c462,#57a957);background-image:-webkit-gradient(linear,0 0,0 100%,from(#62c462),to(#57a957));background-image:-webkit-linear-gradient(top,#62c462,#57a957);background-image:-o-linear-gradient(top,#62c462,#57a957);background-image:linear-gradient(to bottom,#62c462,#57a957);background-repeat:repeat-x;filter:progid:dximagetransform.microsoft.gradient(startColorstr='#ff62c462',endColorstr='#ff57a957',GradientType=0)}
</style>
<form name="options" onsubmit="return false;">
	<div class="panel panel-default">

		<div class="panel-tab-content tab-content">
            <div class="tab-pane active" id="options">
		    	<div class="table-responsive">
				    <table class="table table-striped options-list">
				        <tbody>
				            <tr>
                                <td class="col-xs-10 col-sm-6 col-md-7 "><h6><b>Для массовой генерации у вас должен быть установлен и настроен модуль Advanced Kodik Parser</b></h6><span class="note large">Перед тем как запустить проставление убедитесь что вы корректно настроили дополнительное поле с ID Shikimori и/или дополнительное поле с ID MyDramaList, а также дополнительное поле c последней вышедшей серией. Также настроили сам Посерийный модуль Kodik</span></td>
                               <td class="col-xs-2 col-md-5 settingstd "></td>
                            </tr>
                            <tr>
                               <td class="col-xs-10 col-sm-6 col-md-7 "><h6><b>Новостей для генерации серий:</b></h6><span class="note large">Общее кол-во полученных новостей для генерации</span></td>
                               <td class="col-xs-2 col-md-5 settingstd "><span id="news-count-update">0</span></td>
                               </tr>
                            <tr>
                               <td class="col-xs-10 col-sm-6 col-md-7 "><h6><b>Обработано новостей:</b></h6><span class="note large">Кол-во новостей, которые были обработаны</span></td>
                               <td class="col-xs-2 col-md-5 settingstd "><span id="current-updated-news">0</span></td>
                            </tr>
                        </tbody>
				    </table>
				    <div class="update-status">
	                    <div class="update-status__current" id="updated-current">0%</div>
	                    <div class="progress progress-success">
		                    <div class="bar" id="updated-bar" style="width: 0%;"></div>
	                    </div>
	                    <div class="update-status__msg" id="result-msg-update">Запустите проставление...После запуска не закрывайте данную страницу пока проставление не будет полностью готово! <b>Если вы настроили загрузку кадров к сериям на сервер то время простановки существенно увеличится</b></div>
                    </div>
				    <div class="update-status">
	                    <div class="update-status__current" id="updated-current-episode">0%</div>
	                    <div class="progress progress-success">
		                    <div class="bar" id="updated-bar-episode" style="width: 0%;"></div>
	                    </div>
	                    <div class="update-status__msg" id="result-msg-update-episode">Ожидаем запуска...</div>
                    </div>
			    </div>
		    </div>

		</div>

		<div class="panel-footer">
			<div class="pull-left">
				<button type="button" class="btn bg-primary-600 btn-sm btn-raised position-left legitRipple" id="mass-generation">Запустить генерацию</button>
				<button type="button" class="btn bg-slate-600 btn-sm btn-raised position-left legitRipple" onclick="location.href = '{$PHP_SELF}?mod={$mod}';return false">Вернуться назад</button>
			</div>
		</div>
	</div>

	<input type="hidden" name="user_hash" value="{$dle_login_hash}">
	<input type="hidden" name="mod" value="{$mod}">
	<input type="hidden" name="action" value="ajax">
	<input type="hidden" name="subaction" value="save_options">
</form>

<script>
$(document).ready(function() {
    $("#mass-generation").click(function() {
        DLEconfirm("Вы уверены что хотите запустить массовую генерацию серий?", "Подтвердите действие", async function YesImReady() {
            try {
                let response = await $.ajax({
                    url: '/engine/ajax/controller.php?mod=kodik_ajax_controller',
                    data: {file: "mass_generation", action: "update_news_get", user_hash: dle_login_hash},
                    response: 'json'
                });

                if (response.status !== 'fail') {
                    await DoNewsGeneration(response);
                }
            } catch (error) {
                console.log(error);
            }
        });
    });
});

async function DoNewsGeneration(data) {
    if (!data) return false;

    let list_news = JSON.parse(data), all_news = 0, current_percent = 0, current = 0, current_upd = 0;
    if (list_news['error']) {
        alert(list_news['error']);
        return false;
    }

    all_news = list_news.length;
    $('#news-count-update').html(all_news);

    for (let temp of list_news) {
        try {
            let result = await $.ajax({
                url: '/engine/ajax/controller.php?mod=kodik_ajax_controller',
                data: {'file': "mass_generation", 'newsid': temp['id'], 'shikiid': temp['shikimori_id'], 'mdlid': temp['mdl_id'], action: "update_news", user_hash: dle_login_hash},
                response: 'json'
            });

            $('#result-msg-update').html('NewsID: ' + temp['id'] + ' - серии генерируются');
            await DoNewsGenerationEpisode(result);

            if (result !== 'error') {
                current_upd++;
                $('#current-updated-news').html(current_upd);
            } else {
                $('#result-msg-update').html('Данные в новости ' + temp['id'] + ' не были проставлены. Возможно в новости указан не существующий id Shikimori/MyDramaList.');
            }
            current++;
            current_percent = Math.ceil((current / all_news) * 100);
            $('#updated-current').html(current_percent + '%');
            $('#updated-bar').css('width', current_percent + '%');
        } catch (error) {
            console.log(error);
        }
    }

    // After processing all news items, call DoNewsGeneration again
    await DoNewsGeneration(data);
}

async function DoNewsGenerationEpisode(data) {
    if (!data) return false;

    let eps_list = JSON.parse(data), all_eps = 0, current_percent_eps = 0, current_eps = 0;
    if (eps_list['error']) {
        alert(eps_list['error']);
        return false;
    }

    all_eps = eps_list['ep_count'];

    for (let index in eps_list['eps_list']) {
        for (let index2 in eps_list['eps_list'][index]) {
            let episode = eps_list['eps_list'][index][index2];
            try {
                let result = await $.ajax({
                    url: '/engine/ajax/controller.php?mod=kodik_ajax_controller',
                    data: {'file': "mass_generation", 'newsid': eps_list['news_id'], 'sez_num': index, 'ep_num': index2, 'ep_data': episode, 'sez_count': eps_list['sez_count'], 'material_title': eps_list['material_title'], action: "update_news_episode", user_hash: dle_login_hash},
                    response: 'text'
                });

                if (result !== 'error') {
                    $('#result-msg-update-episode').html('NewsID: ' + eps_list['news_id'] + ' ' + index + ' сезон ' + index2 + ' серия добавлена');
                } else {
                    $('#result-msg-update-episode').html('Возникла ошибка при попытке добавить NewsID: ' + eps_list['news_id'] + ' ' + index + ' сезон ' + index2 + ' серия. Попробуйте снова');
                }

                current_eps++;
                current_percent_eps = Math.ceil((current_eps / all_eps) * 100);
                $('#updated-current-episode').html(current_percent_eps + '%');
                $('#updated-bar-episode').css('width', current_percent_eps + '%');
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>
HTML;

?>
