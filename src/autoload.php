<?php

namespace Api;

spl_autoload_register(function ($class) {
    if (strpos($class, 'Api\\') === 0) {
        $name = substr($class, strlen('Api'));
        require __DIR__ . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});