<?php
require_once 'autoloader.php';
/**
 * Class TestList загружает список тестов и затем рисует их
 */
class TestList
{
	private $list = [];

	/**
	 * Получает список тестов
	 * @return array список тестов
	 */
	public function getList()
	{
		return $this->list;
	}

	public function render()
	{
		include("view/view_qlist.php");
	}

	public function __construct()
	{
		$dirHandler = opendir('../tests_json/');
		while ($file = readdir($dirHandler)) {
			$file = substr($file, 0, -5);
			if ($file) array_push($this->list, $file);
		}
		closedir($dirHandler);
	}
}

//класс вопроса
abstract class Question
{
	private $num;

	protected function getNum()
	{
		return $this->num;
	}

	private $QJSON;

	protected function getQJSON()
	{
		return $this->QJSON;
	}

	abstract public function render();

	public function __construct($num, $QJSON)
	{
		$this->num = $num;
		$this->QJSON = $QJSON;
	}
}

//класс для завершенного вопроса
abstract class SubmitedQuestion extends Question
{
	abstract public function isCorrect();

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

//текстовый вопрос
class TextQuestion extends Question
{
	public function render()
	{
		include("view/view_textQ.php");
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

class SubmitedTextQuestion extends SubmitedQuestion
{
	public function render()
	{
		include("view/view_textQC.php");
	}

	public function text()
	{
		return isset($_POST[$this->getNum()]) ? $_POST[$this->getNum()] : "";
	}

	public function isCorrect()
	{
		return isset($_POST[$this->getNum()]) && $_POST[$this->getNum()] == $this->getQJSON()['answer'];
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

//чекбокс вопрос
class CheckBoxQuestion extends Question
{
	public function render()
	{
		include("view/view_checkBoxQ.php");
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

class SubmitedCheckboxQuestion extends SubmitedQuestion
{
	public function render()
	{
		include("view/view_checkBoxQC.php");
	}

	public function isset($num)
	{
		return isset($_POST[$this->getNum() . '+' . $num]);
	}

	public function isAnswer($num)
	{
		if (gettype($this->getQJSON()["answer"]) == "integer") return $this->getQJSON()["answer"] == $num + 1;
		else return in_array($num + 1, $this->getQJSON()["answer"]);
	}

	public function isCheckCorrect($num)
	{
		return $this->isset($num) == $this->isAnswer($num);
	}

	public function isCorrect()
	{
		for ($i = 0; $i < count($this->getQJSON()['variations']); $i++) if (!$this->isCheckCorrect($i)) return false;
		return true;
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

//радио вопрос
class RadioQuestion extends Question
{
	public function render()
	{
		include("view/view_radioQ.php");
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

class SubmitedRadioQuestion extends SubmitedQuestion
{
	public function render()
	{
		include("view/view_radioQC.php");
	}

	public function isset($num)
	{
		return isset($_POST[$this->getNum()]) && $_POST[$this->getNum()] == $num;
	}

	public function isAnswer($num)
	{
		return $this->getQJSON()["answer"] == $num + 1;
	}

	public function isCheckCorrect($num)
	{
		return $this->isset($num) == $this->isAnswer($num);
	}

	public function isCorrect()
	{
		for ($i = 0; $i < count($this->getQJSON()['variations']); $i++) if (!$this->isCheckCorrect($i)) return false;
		return true;
	}

	public function __construct($num, $QJSON)
	{
		parent::__construct($num, $QJSON);
	}
}

//тест включает классы вопросов
class Test
{
	private $questions = [];

	public function render()
	{
		include("view/view_test.php");
	}

	public function __construct($JSON)
	{
		for ($i = 0; $i < count($JSON); $i++) {
			if (!isset($JSON[$i]["variations"])) {
				array_push($this->questions, new TextQuestion($i, $JSON[$i]));
			} elseif ($JSON[$i]["type_variations"] == "checkbox") {
				array_push($this->questions, new CheckBoxQuestion($i, $JSON[$i]));
			}
			else array_push($this->questions, new RadioQuestion($i, $JSON[$i]));
		}
	}
}

//отвеченый тест включает классы отвеченых вопросов
class SubmitedTest
{
	private $submitedQuestions = [];

	public function all()
	{
		return count($this->submitedQuestions);
	}

	public function correct()
	{
		$result = 0;
		foreach ($this->submitedQuestions as $sq) if ($sq->isCorrect()) $result++;
		return $result;
	}

	public function render()
	{
		include("view/view_submitedTest.php");
	}

	public function __construct($JSON)
	{
		for ($i = 0; $i < count($JSON); $i++) {
			if (!isset($JSON[$i]["variations"])) array_push($this->submitedQuestions, new SubmitedTextQuestion($i, $JSON[$i]));
			elseif ($JSON[$i]["type_variations"] == "checkbox") array_push($this->submitedQuestions, new SubmitedCheckBoxQuestion($i, $JSON[$i]));
			else array_push($this->submitedQuestions, new SubmitedRadioQuestion($i, $JSON[$i]));
		}
	}
}

?>
