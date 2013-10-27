<?php

require 'common.php';

list($site, $command) = explode(' ', $query);

if ($command) {
    $path = $_SERVER['HOME'] . '/Sites/' . $site;

    exec('cd ' . $path . ' && powder ' . $command, $output);
} else {
    exec('open http://' . $site . '.dev');
}

echo implode(PHP_EOL, $output);
