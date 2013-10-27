<?php

ini_set('display_errors','Off');
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php-error.log');

require_once 'workflows.php';

$query = trim($argv[1]);
$arguments = explode(' ', $query);

$cache = $_SERVER['HOME'] . '/Library/Application Support/Alfred 2/Workflow Data/steve.pow';

if (!is_dir($cache)) {
    mkdir($cache);
}

$commands_cache_file = $cache . '/powder.json';

if (filemtime($commands_cache_file) < time() - 60 * 60) {
    $handler = fopen($commands_cache_file, 'w+');

    exec('powder', $commands);

    $commands = array_filter($commands, function($command) {
        return strpos($command, 'powder');
    });

    $commands = array_combine(
        array_map(
            function($command) {
                return preg_replace('|\s+powder\s+([^\s]+).*#\s+.*|', '\1', $command);
            },
            $commands
        ),
        array_map(
            function($command) {
                return preg_replace('|\s+powder.*#\s+(.*)|', '\1', $command);
            },
            $commands
        )
    );

    fwrite($handler, json_encode($commands));
} else {
    $handler = fopen($commands_cache_file, 'r');

    $commands = (array) json_decode(fread($handler, filesize($commands_cache_file)));
}
