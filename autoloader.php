<?php
function autoload($className)
{
    $basePath = __DIR__;

    if (file_exists($basePath . '/Controller/' . ucfirst($className) . '.php')) {
        include_once $basePath . '/Controller/' . ucfirst($className) . '.php';
    } else if (file_exists($basePath . '/Model/' . $className . '.php')) {
        include_once $basePath . '/Model/' . $className . '.php';
    } else {
        include_once $basePath . '/' . $className . '.php';
    }
}

spl_autoload_register('autoload');
