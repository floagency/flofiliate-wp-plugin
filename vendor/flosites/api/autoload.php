<?php
/**
 * @author AlexanderC
 */

spl_autoload_register(function ($class) {
    $rawParts = explode("\\", $class);

    if (count($rawParts) <= 0) {
        return false;
    }

    if ($rawParts[0] == "FloFilliate") {
        $path = __DIR__ . '/lib/';
        $parts = $rawParts;
        array_shift($parts);
        $file = realpath($path . implode("/", $parts) . ".php");

        return is_file($file) ? require $file : false;
    } else { // else try to find in vendors
        $path = __DIR__ . '/vendor/';
        $parts = & $rawParts;
        $file = realpath($path . implode("/", $parts) . ".php");

        return is_file($file) ? require $file : false;
    }
});