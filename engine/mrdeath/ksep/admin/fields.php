<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$breadcrumbs = ['?mod=' . $mod => $descr, '' => 'Поля'];

$fields = ['season' => '', 'episode' => ''];

foreach (['season', 'episode'] as $type) {
	if ( empty($series_options['fields'][$type]) ) {
		$fields[$type] = '<li class="dd-list-empty" style="padding: 5px 0px;">Полей нет</li>';
		continue;
	}

	foreach ($series_options['fields'][$type] as $name => $field) {
		$fields[$type] .= '<li class="dd-item" id="field_' . $type . '_' . $name . '"><div class="dd-handle"></div><div class="dd-content"><b id="x_name" class="s-el">' . $field[0] . '</b><b id="x_cats" class="s-el">Описание: ' . $field[1] . '</b><b id="x_type" class="s-el">' . $field[3] . '</b><b class="s-el" style="display:none;">При желании: ' . ($field[5] == 'on' ? 'Да' : 'Нет') . '</b><div style="float:right;"><a href="#" onclick="edit_field(\'' . $name . '\', \'' . $type . '\');return false"><i title="правка" alt="правка" class="fa fa-pencil-square-o position-left"></i></a><a href="#" onclick="del_field(\'' . $name . '\', \'' . $type . '\');return false"><i title="удалить" alt="удалить" class="fa fa-trash-o position-right text-danger"></i></a></div></div></li>';
	}
}

$content = <<<HTML
<form id="series" onsubmit="return false">
	<div class="panel panel-default">
		<div class="panel-heading">
			Поля
		</div>

       	<div class="panel-body">
           	<a href="#" onclick="add_field('season');return false" class="pull-right"><i class="fa fa-plus position-left"></i> Добавить поле</a>

           	<h6 class="media-heading text-semibold">Поля для сезонов</h6>

			<div class="dd" id="nestable-season">
				<ol class="dd-list">
					{$fields['season']}
				</ol>
			</div>
	    </div>

    	<div class="panel-body">
			<a href="#" onclick="add_field('episode');return false" class="pull-right" title="Добавить поле"><i class="fa fa-plus position-left"></i> Добавить поле</a>

        	<h6 class="media-heading text-semibold" style="display: inline-block;">Поля для серий</h6>

			<div class="dd" id="nestable-episode">
				<ol class="dd-list">
					{$fields['episode']}
				</ol>
			</div>
	    </div>

		<div class="panel-footer">
			<div class="pull-left">
				<button type="button" class="btn bg-slate-600 btn-sm btn-raised position-left legitRipple" onclick="location.href = '{$PHP_SELF}?mod={$mod}';return false">Вернуться назад</button>
			</div>
		</div>
	</div>

	<input type="hidden" name="user_hash" value="{$dle_login_hash}">
	<input type="hidden" name="mod" value="series">
	<input type="hidden" name="action" value="save">
</form>

<div id="form_field" title="Добавить поле" style="display: none">
	<form name="form_field" class="form-horizontal">
		<div class="form-group">
			<label class="control-label col-md-3">Название поля</label>
			<div class="col-md-9">
				<input class="form-control width-200" maxlength="30" type="text" name="field[0]" value="" /><span class="text-muted text-size-small"><i class="fa fa-exclamation-circle position-left position-right"></i>Латинскими буквами</span>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Описание поля</label>
			<div class="col-md-9">
				<input class="form-control width-400" maxlength="100" type="text" name="field[1]" value="" />
			</div>
		</div>

		<div class="form-group" style="display:none!important;">
			<label class="control-label col-md-3">Подсказка для поля</label>
			<div class="col-md-9">
				<input class="form-control width-400" maxlength="200" type="text" name="field[18]" value="" placeholder="Введите текст подсказки для заполнения данного поля" />
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Тип поля</label>
			<div class="col-md-9">
				<select class="uniform" name="field[3]" id="type" onchange="onTypeChange(this.value);">
					<option value="text" selected>Одна строка</option>
					<option value="textarea">Несколько строк</option>
					<!-- <option value="htmljs">Чистый HTML или JS код</option>
					<option value="select">Список</option> -->
					<option value="image">Загружаемое изображение</option>
					<!-- <option value="imagegalery">Загружаемая галерея изображений</option>
					<option value="file">Загружаемый файл</option>
					<option value="yesorno">Переключатель 'Да' или 'Нет'</option> -->
				</select>
			</div>
		</div>
        
        <div id="default_image">

			<div class="form-group">
				<label class="control-label col-md-3">Минимальные размеры изображения для загрузки</label>
				<div class="col-md-9">
					<input class="form-control text-center" style="width:100%;max-width: 100px;" type="text" name="field[22]" value="" /><i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right position-left" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Вы можете задать минимальные размеры по ширине и высоте для изображения которое будет загружено на сервер. Вы можете задать только размер одной стороны, например: 800, либо можете задать размеры сразу двух сторон, например: 800x600. Если вы не хотите устанавливать ограничения, то оставьте поле пустым." ></i>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Максимальные размеры оригинального изображения</label>
				<div class="col-md-9">
					<input class="form-control text-center" style="width:100%;max-width: 100px;" type="text" name="field[9]" value="" /><i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right position-left" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Вы можете задать максимальные размеры по ширине и высоте для изображения которое будет загружено на сервер, размеры данного изображения будут уменьшены до указанных. Вы можете задать только размер одной стороны, например: 800, либо можете задать размеры сразу двух сторон, например: 800x600. Если вы не хотите устанавливать ограничения, то оставьте поле пустым." ></i>
				</div>
			</div>
            
			<div class="form-group">
				<label class="control-label col-md-3">Максимальный вес изображения</label>
				<div class="col-md-9">
					<input class="form-control text-center" style="width:100%;max-width: 100px;" type="text" name="field[10]" value="" /><i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right position-left" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Введите максимально допустимый вес загружаемого изображения в килобайтах. Если не хотите устанавливать ограничения, то можете оставить это поле пустым" ></i>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-9">
					<div class="checkbox"><label><input  class="icheck" type="checkbox" name="field[11]" id="editx11" />Наложить водяные знаки</label></div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-9">
					<div class="checkbox"><label><input  class="icheck" type="checkbox" name="field[12]" id="editx12" />Создать уменьшенную копию</label></div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Размеры уменьшенной копии</label>
				<div class="col-md-9">
					<input class="form-control text-center" style="width:100%;max-width: 100px;" type="text" name="field[13]" value="" /><i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right position-left" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Вы можете задать размер только одной стороны, например: 200, либо можете задать размеры сразу двух сторон, например: 150x100." ></i>

					&nbsp;&nbsp;&nbsp;&nbsp;

					<select class="uniform" name="field[13_seite]">
						<option value="0" selected>По наибольшей стороне</option>
						<option value="1">По ширине</option>
						<option value="2">По высоте</option>
					</select>
				</div>
			</div>
		</div>
		
	</form>
</div>

<script type="text/javascript">
function ShowOrHideEx(id, show) {
	var item = null;

	if (document.getElementById) {
		item = document.getElementById(id);
	} else if (document.all) {
		item = document.all[id];
	} else if (document.layers){
		item = document.layers[id];
	}

	if (item && item.style) {
		item.style.display = show ? "" : "none";
	}
}

function onTypeChange(value) {
    //ShowOrHideEx("default_text", value == "text");
    //ShowOrHideEx("optional2", value == "text" || value == "select");
    //ShowOrHideEx("optional7", value == "text" || value == "select");
    //ShowOrHideEx("default_textarea", value == "textarea" || value == "htmljs");
    //ShowOrHideEx("optional3", value == "textarea");
    //ShowOrHideEx("optional4", value == "text" || value == "textarea");
    //ShowOrHideEx("select_options", value == "select");
    //ShowOrHideEx("optional", value != "select" && value != "yesorno");
    ShowOrHideEx("default_image", value == "image" || value == "imagegalery");
	//ShowOrHideEx("optional5", value == "imagegalery");
	//ShowOrHideEx("optional6", value == "yesorno");
	//ShowOrHideEx("default_file", value == "file");
	//ShowOrHideEx("default_htmljs", value == "htmljs");
}

function show_form(bnt_text, btn_callback) {
	var b = {};

	b['Отмена'] = function() {
		$(this).dialog('close');
	};

	b[bnt_text] = btn_callback;

	$('#form_field').dialog({
		width: 800,
		height: 600,
		buttons: b
	});
}

function add_field(type) {
	$('form[name=form_field]')[0].reset();

	$('form[name=form_field] select').trigger('change');

	$('form[name=form_field] input[name="field\\[0\\]"]').prop('readonly', false);

	$.uniform.update();

	var item_type = document.getElementById("type");
	var item_category = document.getElementById("category");

	if (item_type) {
		onTypeChange(item_type.value);
	}

	show_form('Добавить', function() {
		var data = $('form[name=form_field]').serialize();

		$(this).dialog('close');

		ShowLoading('');

		$.ajax({
			url: '?mod={$mod}&action=ajax&subaction=add_field&type=' + type,
			data: data,
			dataType: 'JSON',
			type: 'POST',
			success: function(result) {
				if ( result.success !== true ) {
					DLEalert(result.message, 'Добавление поля');
					return false;
				}

				$('#nestable-' + type + ' > ol> li.dd-list-empty').remove();
				$('#nestable-' + type + ' > ol').append(result.response);

				Growl.info({
					title: 'Информация',
					text: 'Поле успешно добавлено'
				});
			},
			error: function() {
				DLEalert('Произошла неизвестная ошибка', 'Произошла ошибка');
			},
			complete: function() {
				HideLoading();
			}
		});
	});

	return false;
}

function edit_field(name, type) {
	ShowLoading('');

	$.ajax({
		url: '{$PHP_SELF}',
		data: {mod: '{$mod}', action: 'ajax', subaction: 'edit_field', name: name, type: type},
		type: 'POST',
		dataType: 'JSON',
		success: function(result) {
			if ( result.success !== true ) {
				DLEalert(result.message, 'Редактирование поля');
				return false;
			}

			$('form[name=form_field]')[0].reset();

			$.each(result.response, function(name, value) {
				if ( name == 3 || name == 17 || name == '13_seite' ) {
					$('form[name=form_field] select[name="field\\[' + name + '\\]"]').val(value);
				} else {
					let input = $('form[name=form_field] input[name="field\\[' + name + '\\]"]');

					if ( input.is('[type=checkbox]') ) {
						if ( input.val() == value ) {
							input.prop('checked', true);
						} else {
							input.prop('checked', false);
						}

						input.trigger('change');
					} else {
						input.val(value).trigger('change');
					}
				}
			});

			$('form[name=form_field] input[name="field\\[0\\]"]').prop('readonly', true);

			$('form[name=form_field] select').trigger('change');

			$.uniform.update();

			var item_type = document.getElementById("type");
			var item_category = document.getElementById("category");

			if (item_type) {
				onTypeChange(item_type.value);
			}

			show_form('Сохранить', function() {
				var data = $('form[name=form_field]').serializeArray();

				$(this).dialog('close');

				ShowLoading('');

				$.ajax({
					url: '?mod={$mod}&action=ajax&subaction=save_field&name=' + name + '&type=' + type,
					data: data,
					dataType: 'JSON',
					type: 'POST',
					success: function(result) {
						if ( result.success !== true ) {
							DLEalert(result.message, 'Редактирование поля');
							return false;
						}

						$('#field_' + type + '_' + name).replaceWith(result.response);

						Growl.info({
							title: 'Информация',
							text: 'Поле успешно изменено'
						});
					},
					error: function() {
						DLEalert('Произошла неизвестная ошибка', 'Произошла ошибка');
					},
					complete: function() {
						HideLoading();
					}
				});
			});
		},
		error: function() {
			DLEalert('Произошла неизвестная ошибка', 'Произошла ошибка');
		},
		complete: function() {
			HideLoading();
		}
	});
}

function del_field(name, type) {
	DLEconfirm('Вы действительно хотите удалить поле "' + name + '"?', 'Удалить поле', function() {
		ShowLoading('');

		$.ajax({
			url: '{$PHP_SELF}',
			data: {mod: '{$mod}', action: 'ajax', subaction: 'del_field', name: name, type: type},
			type: 'POST',
			dataType: 'JSON',
			success: function(result) {
				if ( result.success !== true ) {
					DLEalert(result.message, 'Добавление поля');
					return false;
				}

				$('#field_' + type + '_' + name).remove();

				if ( $('#nestable-' + type + ' > ol > li').length == 0 ) {
					$('#nestable-' + type + ' > ol').append('<li class="dd-list-empty" style="padding: 5px 0px;">Полей нет</li>');
				}

				Growl.info({
					title: 'Информация',
					text: 'Поле успешно удалено'
				});
			},
			error: function() {
				DLEalert('Произошла неизвестная ошибка', 'Произошла ошибка');
			},
			complete: function() {
				HideLoading();
			}
		});
	});

	return false;
}
</script>
HTML;

?>
