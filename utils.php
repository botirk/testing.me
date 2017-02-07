<?php
	//список тестов
	class TestList {
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
	//класс вопроса
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
	//класс для завершенного вопроса
	abstract class SubmitedQuestion extends Question {
		abstract public function isCorrect();
		
		public function __construct($num, $QJSON) { parent::__construct($num, $QJSON); }
	}
	//текстовый вопрос
	class TextQuestion extends Question {
		public function render() { include("view/view_textQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	class SubmitedTextQuestion extends SubmitedQuestion {
		public function render() { include("view/view_textQC.php"); }
		public function text() { return isset($_POST[$this->getNum()]) ? $_POST[$this->getNum()] : ""; }
		public function isCorrect() { return isset($_POST[$this->getNum()]) && $_POST[$this->getNum()] == $this->getQJSON()['answer']; }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	//чекбокс вопрос
	class CheckBoxQuestion extends Question {
		public function render() { include("view/view_checkBoxQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	class SubmitedCheckboxQuestion extends SubmitedQuestion {
		public function render() { include("view/view_checkBoxQC.php"); }
		public function isset($num) { return isset($_POST[$this->getNum().'+'.$num]); }
		public function isAnswer($num) {
			if (gettype($this->getQJSON()["answer"]) == "integer") return $this->getQJSON()["answer"] == $num+1;
			else return in_array($num+1, $this->getQJSON()["answer"]);
		}
		public function isCheckCorrect($num) { return $this->isset($num) == $this->isAnswer($num); }
		public function isCorrect() {
			for($i=0;$i<count($this->getQJSON()['variations']);$i++) if (!$this->isCheckCorrect($i)) return false;
			return true;
		}
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	//радио вопрос
	class RadioQuestion extends Question {
		public function render() { include("view/view_radioQ.php"); }
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	class SubmitedRadioQuestion extends SubmitedQuestion {
		public function render() { include("view/view_radioQC.php"); }
		public function isset($num) { return isset($_POST[$this->getNum()]) && $_POST[$this->getNum()] == $num; }
		public function isAnswer($num) { return $this->getQJSON()["answer"] == $num+1; }
		public function isCheckCorrect($num) { return $this->isset($num) == $this->isAnswer($num); }
		public function isCorrect() {
			for($i=0;$i<count($this->getQJSON()['variations']);$i++) if (!$this->isCheckCorrect($i)) return false;
			return true;
		}
		public function __construct($num,$QJSON) { parent::__construct($num,$QJSON); }
	}
	//тест включает классы вопросов
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
	//отвеченый тест включает классы отвеченых вопросов
	class SubmitedTest {
		private $submitedQuestions = array();
		public function all() { return count($this->submitedQuestions); }
		public function correct() { $result = 0; foreach($this->submitedQuestions as $sq) if ($sq->isCorrect()) $result++; return $result; }
		public function render() { include("view/view_submitedTest.php"); }
		public function __construct($JSON) {
			for ($i=0;$i<count($JSON);$i++) {
				if (!isset($JSON[$i]["variations"])) array_push($this->submitedQuestions, new SubmitedTextQuestion($i,$JSON[$i]));
				elseif ($JSON[$i]["type_variations"] == "checkbox") array_push($this->submitedQuestions, new SubmitedCheckBoxQuestion($i,$JSON[$i]));
				else array_push($this->submitedQuestions, new SubmitedRadioQuestion($i,$JSON[$i]));
			}
		}
	}
?>
