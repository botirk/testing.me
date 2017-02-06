<?php

class Template
{
    public function render($name, $params = [])
    {
        // $params = ['tests' => .....];
        // Рендерим шапку в отдельном методе
        // Подключать файл и вывести его в отдельном методе
        extract($params);
        include $name . '.php';

        // Рендерим футер в отдельном методе
    }

    public function renderFooter()
    {

    }
}
