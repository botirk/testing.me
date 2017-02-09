<?php

class Config
{
    private static $_config;

    public static function getConfig($name = null)
    {
        if (!self::$_config) {
            self::$_config = include ROOT . 'config.php';
        }

        if ($name && !isset(self::$_config[$name])) {
            throw new Exception();
        }
        return $name ? self::$_config[$name] : self::$_config;
    }
}