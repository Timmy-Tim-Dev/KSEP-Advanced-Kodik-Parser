<?php
 
if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
	die('Hacking attempt!');
}

function showRow($title = "", $description = "", $field = "", $class = "") {
	echo "<tr>
       <td class=\"col-xs-10 col-sm-6 col-md-7 {$class}\"><h6><b>{$title}:</b></h6><span class=\"note large\">{$description}</span></td>
       <td class=\"col-xs-2 col-md-5 settingstd {$class}\">{$field}</td>
       </tr>";
}

function showInput($data)
{
	$input_elemet = $data[3] ? " placeholder=\"{$data[3]}\"" : '';
	$input_elemet .= $data[4] ? ' disabled' : '';
	if ($data[1] == 'range') {
		$class = ' custom-range';
		$input_elemet .= $data[5] ? " step=\"{$data[5]}\"" : '';
		$input_elemet .= $data[6] ? " min=\"{$data[6]}\"" : '';
		$input_elemet .= $data[7] ? " max=\"{$data[7]}\"" : '';
	} elseif ($data[1] == 'number') {
		$class = ' w-9';
		$input_elemet .= $data[5] ? " min=\"{$data[5]}\"" : '';
		$input_elemet .= $data[6] ? " max=\"{$data[6]}\"" : '';
	}
return <<<HTML
	<input type="{$data[1]}" autocomplete="off" style="float: right;" value="{$data[2]}" class="form-control{$class}" name="{$data[0]}"{$input_elemet}>
HTML;
}

function showtextarea($name)
{
echo <<<HTML
<tr>
	<td>
		<label style="float:left;" class="form-label"><b>{$name}</b></label>
        <textarea id="url-list" style="min-height:150px;max-height:150px;min-width:333px;max-width:100%;border: 1px solid #ddd;padding: 5px;" autocomplete="off" class="form-control" name="url-list" placeholder="Каждая ссылка с новой строки, лимит 100 ссылок за раз"></textarea>
        <button onclick="SendMass(); return false;" class="btn bg-slate-600 btn-raised position-left"><i class="fa fa-envelope-o position-left"></i>Отправить</button>
    </td>
</tr>
HTML;
}

function makeCheckBox($name, $selected, $function_name = false)
{
		$selected = $selected ? "checked" : "";
		if ( $function_name == "ShowOrHidePlayer" ) return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" id=\"player_on_off\" value=\"1\" onchange=\"$function_name();\" {$selected}>";
		elseif ( $function_name == "ShowOrHidePush" ) return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" id=\"push_on_off\" value=\"1\" onchange=\"$function_name();\" {$selected}>";
		elseif ( $function_name == "ShowOrHideRooms" ) return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" id=\"rooms_on_off\" value=\"1\" onchange=\"$function_name();\" {$selected}>";
		elseif ( $function_name == "ShowOrHideGindexing" ) return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" id=\"google_indexing\" value=\"1\" onchange=\"$function_name();\" {$selected}>";
		elseif ( $function_name == "ShowOrHideTg" ) return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" id=\"tg_on_off\" value=\"1\" onchange=\"$function_name();\" {$selected}>";
		else return "<input class=\"switch\" type=\"checkbox\" name=\"{$name}\" value=\"1\" {$selected}>";
}

function showSelect($name, $value, $check = false)
{
	if(!$check) $multiple = "multiple";
	return "<select data-placeholder=\""."".$phrases_settings['category_chose']."\" name=\"{$name}\" id=\"category\" class=\"valueselect\" {$multiple} style=\"width:100%;max-width:350px;\">{$value}</select>";
}

function makeDropDown($options, $name, $selected, $function_name = false) {
        if ( $function_name ) $output = "<select class=\"uniform\" style=\"min-width:100px;\" name=\"$name\" onchange=\"$function_name(this.value)\">\r\n";
        else $output = "<select class=\"uniform\" style=\"min-width:100px;\" name=\"$name\" id=\"$name\">\r\n";
        foreach ( $options as $value => $description ) {
            $output .= "<option value=\"$value\"";
            if( $selected == $value ) {
                $output .= " selected ";
            }
            $output .= ">$description</option>\n";
        }
        $output .= "</select>";
        return $output;
    }
    
function makeDropDownAlt($options, $name, $selected) {
	$output = "<select class=\"uniform\" style=\"opacity:0;\" name=\"$name\" id=\"$name\">\r\n";
	$output .= "<option value=''>Выберите файл</option>";
	foreach ( $options as $value => $description ) {
		$output .= "<option value=\"$description\"";
		if( $selected == $description ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}

function showTrInline($name, $description, $type, $data)
{
echo <<<HTML
<tr>
	<td>
		<label style="float:left;" class="form-label"><b>{$name}</b></label>
HTML;
	switch ($type) {
		case 'input':
			echo showInput($data);
		break;
		case 'textarea':
			echo textareaForm($data);
		break;
		default:
			echo $data;
		break;
	}
echo <<<HTML
</tr>
HTML;
}
	
function textareaForm($data)
{
	$input_elemet = $data[2] ? " placeholder=\"{$data[2]}\"" : '';
	$input_elemet .= $data[3] ? ' disabled' : '';
return <<<HTML
	<textarea style="min-height:150px;max-height:150px;min-width:333px;max-width:100%;border: 1px solid #ddd;padding: 5px;" autocomplete="off" class="form-control" name="{$data[0]}"{$input_elemet}>{$data[1]}</textarea>
HTML;
}

function ShowSelected($data)
{
	foreach ($data[1] as $key => $val) {
		if ($data[2]) {
			$output .= "<option value=\"{$key}\"";
		} else {
			$output .= "<option value=\"{$val}\"";
		}
		if (is_array($data[3])) {
			foreach ($data[3] as $element) {
				if ($data[2] && $element == $key) {
					$output .= " selected ";
				} elseif (!$data[2] && $element == $val) {
					$output .= " selected ";
				}
			}
		} elseif ($data[2] && $data[3] == $key) {
			$output .= " selected ";
		} elseif (!$data[2] && $data[3] == $val) {
			$output .= " selected ";
		}
		$output .= ">{$val}</option>\n";
	}
	$input_elemet = $data[5] ? ' disabled' : '';
	$input_elemet .= $data[4] ? ' multiple' : '';
	$input_elemet .= $data[6] ? " data-placeholder=\"{$data[6]}\"" : '';
return <<<HTML
<select name="{$data[0]}" class="form-control custom-select" {$input_elemet}>
	{$output}
</select>
HTML;
}

function makeSelect($array, $name, $data, $placeholder, $mode)
{
    $ar_ray = explode(',', $data);
    $options = [];
    foreach ($array as $key => $value) {
        if ( $mode == 1 ) $key = $value;
	    if (in_array($key, $ar_ray)) {
	    	$options[] = '<option value="'.$key.'" selected>'.$value.'</option>';
	    }
	    else {
	    	$options[] = '<option value="'.$key.'">'.$value.'</option>';
	    }
    }
    if ( $options ) return '<select data-placeholder="'.$placeholder.'" name="'.$name.'[]" id="'.$name.'" class="valuesselect" multiple style="width:100%;max-width:350px;">'.implode('', $options).'</select>';
    else return '<select data-placeholder="'.$placeholder.'" name="'.$name.'[]" id="'.$name.'" class="valuesselect" multiple style="width:100%;max-width:350px;"></select>';
}