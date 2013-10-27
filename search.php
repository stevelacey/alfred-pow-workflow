<?php

require_once 'common.php';

$sites = glob($_SERVER['HOME'] . '/Sites/' . $arguments[0] . '*');
$action = isset($arguments[1]) ? $arguments[1] : false;

$w = new Workflows;

if (isset($action)) {
    $commands = array_intersect_key(
        $commands,
        array_flip(
            array_filter(
                array_keys($commands),
                function($command) use ($action) {
                    return preg_match('|^' . $action . '|', $command);
                }
            )
        )
    );
}

$response = array();

foreach ($sites as $site) {
    $name = basename($site);

    foreach ($commands as $command => $description) {
        $full = $name . ' ' . $command;

        if (strpos($description, 'current')) {
            $subtitle = str_replace('current', $name, $description);
        } else {
            $subtitle = $description . ' for ' . $name;
        }

        $response[] = array_filter(
            array(
                'uid' => md5($name . '-' . $command),
                'title' => 'pow ' . $full,
                'subtitle' => $subtitle,
                'icon' => 'icon.png',
                'arg' => $full,
                'autocomplete' => $query != $full ? $full : null,
                'valid' => $query == $full ? 'yes' : 'no'
            )
        );
    }
}

echo $w->toXML($response);
