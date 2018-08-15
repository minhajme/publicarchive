<?php
// all empty fields will be filled/imported runtime
$_config = [
    'root_path' => '',
    'root_uri' => '',
    'root_url' => '',
    'templates_dir' => dirname(__DIR__) . '/templates',
    'tmp_dir' => '',
    'db_datetime_format' => 'Y-m-d G:i:s',
    'bikroy_datetime_format' => 'd M g:i a',
    'db' => [
        'default' => [
            'host' => 'localhost',
            'database' => 'crawler',
            'user' => 'crawler',
            'password' => 'crawler_secret',
            'port' => '',
            'prefix' => ''
        ]]
];

return $_config;