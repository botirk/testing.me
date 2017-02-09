<?php

function loader_classes($class)
{
    include __DIR__ . '/classes/' . $class . '.php';
}

spl_autoload_register('loader_classes');