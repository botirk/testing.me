<?php
	if (!isset($_FILES['json']))
		echo json_encode(array('error' => 'Ошибка: Файл не обнаружен'));
	elseif (!is_uploaded_file($_FILES['json']['tmp_name']))
		echo json_encode(array('error' => 'Ошибка: Файл не был загружен'));
	elseif ($_FILES['json']['type'] !== 'application/json')
		echo json_encode(array('error' => 'Ошибка: Тип файла должен быть JSON'));
	elseif ($_FILES['json']['size'] > 1024 * 512)
		echo json_encode(array('error' => 'Ошибка: Размер файла должен быть меньше 512кб'));
	elseif ($_FILES['json']['size'] + 1024 * 512 > disk_free_space('/'))
		echo json_encode(array('error' => 'Ошибка: Не хватает места на сервере'));
	elseif (file_exists('../tests_json/'.$_FILES['json']['name']))
		echo json_encode(array('error' => 'Ошибка: Файл уже существует'));
	else {
		//check JSON
		$json = json_decode(file_get_contents($_FILES['json']['tmp_name']));
		if ($json === NULL || $json === FALSE || $json === TRUE)
			echo json_encode(array('error' => 'Ошибка: JSON не распарсился'));
		else {
			foreach($json as $q) {
				if ($q.question !== null || $q.id !== null || $q.answer !== null){
					echo json_encode(array('error' => 'Ошибка: Плохое содержание файла: '.$q));
					die;
				}
			}
			if (!move_uploaded_file($_FILES['json']['tmp_name'],'../tests_json/'.$_FILES['json']['name'])) 
				echo json_encode(array('error' => 'Ошибка: Файл не был загружен'));
			else 
				echo json_encode(array('success' => $_FILES['json']['name']));
			
		}
	}
?>
