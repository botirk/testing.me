<?php

class Test
{
    protected $id;
    private $_testData;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function getQuestions()
    {
        if (!$this->_testData) {
            $this->_testData = json_decode(file_get_contents($this->id . '.json'), true);
        }
        return $this->_testData;
    }

    public function getMark()
    {
        // $this->getRightAnswers();

        // Высчитываем оценку
        // $this->_testData;
    }

    /**
     * Проверка правильных отвеченных вопросов
     * @return integer Возвращает количество верных ответов.
     */
    public function getRightAnswers()
    {

    }
}