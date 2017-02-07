<?php
	class QuestionList {
		private $list = array();
		public function getList() { return $this->list; }
		
		public function render() { include("view/view_qlist.php"); }
		public function __construct() {
			$dirHandler = opendir('../tests_json/');
			while($file = readdir($dirHandler)) {
				$file = substr($file,0,-5);
				if ($file) array_push($this->list,$file);
			}
			closedir($dirHandler);
		}
	}
	
	abstract class Question {
		private $num;
		protected function getNum() { return $this->num; }
		
		private $QJSON;
		protected function getQJSON() { return $this->QJSON; }
		
		abstract public function render();
		
		public function __construct($num, $QJSON) { 
			$this->num = $num;
			$this->QJSON = $QJSON; 
		}
	}
	
	abstract class SubmitedQuestion extends Question {
		abstract public function isCorrect();
		
		public function __construct($num, $QJSON) { parent::__construct($num, $QJSON); }
	}
	
	class TextQuestion extends Question {
		public function render() { include("view/view_textQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	
	class CheckBoxQuestion extends Question {
		public function render() { include("view/view_checkBoxQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	
	class RadioQuestion extends Question {
		public function render() { include("view/view_radioQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	
	class Test {
		private $questions = array();
		public function render() { include("view/view_test.php"); }
		public function __construct($JSON) {
			for ($i=0;$i<count($JSON);$i++) {
				if (!isset($JSON[$i]["variations"])) array_push($this->questions, new TextQuestion($i,$JSON[$i]));
				elseif ($JSON[$i]["type_variations"] == "checkbox") array_push($this->questions, new CheckBoxQuestion($i,$JSON[$i]));
				else array_push($this->questions, new RadioQuestion($i,$JSON[$i]));
			}
		}
		
		public static function upload() {
			switch (true) {
				case !isset($_FILES['json']):
					return json_encode(['error' => 'Ошибка: Файл не обнаружен']);
				case !is_uploaded_file($_FILES['json']['tmp_name']):
					return json_encode(['error' => 'Ошибка: Файл не был загружен']);
				case $_FILES['json']['type'] !== 'application/json':
					return json_encode(['error' => 'Ошибка: Тип файла должен быть JSON']);
				case $_FILES['json']['size'] > 1024 * 512:
					return json_encode(['error' => 'Ошибка: Размер файла должен быть меньше 512кб']);
				case $_FILES['json']['size'] + 1024 * 512 > disk_free_space('/'):
					return json_encode(['error' => 'Ошибка: Не хватает места на сервере']);
				case file_exists('../tests_json/'.$_FILES['json']['name']):
					return json_encode(['error' => 'Ошибка: Файл уже существует']);
				case !json_decode(file_get_contents($_FILES['json']['tmp_name'])):
					return json_encode(['error' => 'Ошибка: JSON не валидный']);
				case !move_uploaded_file($_FILES['json']['tmp_name'],__DIR__.'/tests_json/'.$_FILES['json']['name']):
					return json_encode(['error' => 'Ошибка: Файл '.$_FILES['json']['name']].' не был обработан сервером');
				default:
					return json_encode(['success' => $_FILES['json']['name']]);
			}
		}
	}
?>
