<?php
define('ROOT', __DIR__ . '/../');
$action = $_GET['action'];
$db = new Db();

if ($action == 'create') {
    // валидация
    // выполняем создание
    $db->insert();
} elseif (..) {

}


// При выводе списка записей не забыть сделать htmlspecialchars или encodentity для предотвращения XSS
