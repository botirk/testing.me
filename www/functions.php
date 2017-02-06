<?php

/**
 * Получает список тестов
 * @return array список тестов
 */
function getTests()
{

    return [];
}

/**
 *
 */
function uploadTest()
{
    // Ваолидация
    // Загрука файла
}

/**
 *
 */
function checkTest()
{

}


function getMark()
{

}

function getParamPost($name, $default = null)
{
    return isset($_POST[$name]) ? $_POST[$name] : $default;
}