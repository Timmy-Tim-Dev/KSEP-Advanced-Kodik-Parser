<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$breadcrumbs = $descr;

$content = <<<HTML
<div class="panel panel-default">
    <div class="panel-heading">
        Список разделов
    </div>

    <div class="list-bordered">
        <div class="row box-section">
            <div class="col-sm-6 media-list media-list-linked">
                <a class="media-link" href="?mod={$mod}&action=options">
                    <div class="media-left">
                        <img src="engine/skins/images/tools.png" class="img-lg section_icon">
                    </div>

                    <div class="media-body">
                        <h6 class="media-heading  text-semibold">Параметры</h6>
                        <span class="text-muted text-size-small">Основные настройки модуля</span>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 media-list media-list-linked">
                <a class="media-link" href="?mod={$mod}&action=fields">
                    <div class="media-left">
                        <img src="engine/skins/images/xfset.png" class="img-lg section_icon">
                    </div>

                    <div class="media-body">
                        <h6 class="media-heading  text-semibold">Поля</h6>
                        <span class="text-muted text-size-small">Список полей для сезонов и серий</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="list-bordered">
        <div class="row box-section">
            <div class="col-sm-6 media-list media-list-linked">
                <a class="media-link" href="?mod={$mod}&action=massgeneration">
                    <div class="media-left">
                        <img src="engine/skins/images/refresh.png" class="img-lg section_icon">
                    </div>

                    <div class="media-body">
                        <h6 class="media-heading  text-semibold">Массовая генерация</h6>
                        <span class="text-muted text-size-small">Массова генерация серий при первой установке</span>
                    </div>
                </a>
            </div>
            
            <div class="col-sm-6 media-list media-list-linked">
                <a class="media-link" href="?mod={$mod}&action=faq">
                    <div class="media-left">
                        <img src="engine/skins/images/question.png" class="img-lg section_icon">
                    </div>

                    <div class="media-body">
                        <h6 class="media-heading  text-semibold">Инструкция</h6>
                        <span class="text-muted text-size-small">Описание тегов разных файлов</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
HTML;

?>
