<?php (defined('DATALIFEENGINE') && defined('LOGGED_IN')) || die('Hacking attempt!');

$subaction = (!empty($_REQUEST['subaction'])) ? $_REQUEST['subaction'] : '';

switch ($subaction) {
    case 'save_options':
        if ( empty($_POST['options']) ) {
            die('{"success":false,"message":"Произошла ошибка."}');
        }

        $options = $_POST['options'];
        $options['fields'] = $series_options['fields'];

        save_con(ENGINE_DIR . '/mrdeath/ksep/data/config.php', $options, false);

        die(json_encode(['success' => true, 'response' => '']));

    case 'add_field': case 'save_field':
        if ( empty($_REQUEST['field']) ) {
            die('{"success":false,"message":"Произошла ошибка"}');
        }

        $type = (!empty($_REQUEST['type'])) ? $_REQUEST['type'] : '';

        if ( $type != 'season' && $type != 'episode' ) {
            die('{"success":false,"message":"Произошла ошибка"}');
        }

        $field = $_REQUEST['field'];

        if ( empty($field[0]) ) {
            die('{"success":false,"message":"Укажите имя поля"}');
        }

        $field[0] = totranslit($field[0], true, false);

        if ( empty($field[0]) ) {
            die('{"success":false,"message":"Укажите имя поля"}');
        }

        if ( empty($field[1]) ) {
            die('{"success":false,"message":"Укажите описание поля"}');
        }

        if ( $subaction == 'add_field' ) {
            if ( isset($series_options['fields'][$type][$field[0]]) ) {
                die('{"success":false,"message":"Поле с таким названием уже существует"}');
            }

            if ( !isset($series_options['fields']) ) {
                $series_options['fields'] = ['season' => [], 'episode' => []];
            }
        } else {
            if ( !isset($series_options['fields'][$type][$field[0]]) ) {
                die('{"success":false,"message":"Поле с таким названием не существует"}');
            }
        }

        $series_options['fields'][$type][$field[0]] = $field;

        save_con(ENGINE_DIR . '/mrdeath/ksep/data/config.php', $series_options, false);

        $response = '<li class="dd-item" id="field_' . $type . '_' . $field[0] . '"><div class="dd-handle"></div><div class="dd-content"><b id="x_name" class="s-el">' . $field[0] . '</b><b id="x_cats" class="s-el">Описание: ' . $field[1] . '</b><b id="x_type" class="s-el">' . $field[3] . '</b><b class="s-el" style="display:none;">При желании: ' . ($field[5] == 'on' ? 'Да' : 'Нет') . '</b><div style="float:right;"><a href="#" onclick="edit_field(\'' . $field[0] . '\', \'' . $type . '\');return false"><i title="правка" alt="правка" class="fa fa-pencil-square-o position-left"></i></a><a href="#" onclick="del_field(\'' . $field[0] . '\', \'' . $type . '\');return false"><i title="удалить" alt="удалить" class="fa fa-trash-o position-right text-danger"></i></a></div></div></li>';

        die(json_encode(['success' => true, 'response' => $response]));

    case 'del_field':
        $name = (!empty($_REQUEST['name'])) ? $_REQUEST['name'] : '';
        $type = (!empty($_REQUEST['type'])) ? $_REQUEST['type'] : '';

        if ( empty($name) || ($type != 'season' && $type != 'episode') ) {
            die('{"success":false,"message":"Произошла ошибка"}');
        }

        if ( !isset($series_options['fields'][$type][$name]) ) {
            die('{"success":false,"message":"Произошла ошибка. Поле не существует."}');
        }

        unset($series_options['fields'][$type][$name]);

        save_con(ENGINE_DIR . '/mrdeath/ksep/data/config.php', $series_options, false);

        die(json_encode(['success' => true, 'response' => '']));

    case 'edit_field':
        $name = (!empty($_REQUEST['name'])) ? $_REQUEST['name'] : '';
        $type = (!empty($_REQUEST['type'])) ? $_REQUEST['type'] : '';

        if ( empty($name) || ($type != 'season' && $type != 'episode') ) {
            die('{"success":false,"message":"Произошла ошибка"}');
        }

        if ( !isset($series_options['fields'][$type][$name]) ) {
            die('{"success":false,"message":"Произошла ошибка. Поле не существует."}');
        }

        die(json_encode(['success' => true, 'response' => $series_options['fields'][$type][$name]]));

    default:
        die('{"success":false,"message":"Произошла ошибка"}');
}

?>
