<?php

date_default_timezone_set('UTC');
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 0);

require __DIR__ . '/autoload.php';

$config = require __DIR__ . '/config/config.php';

$config = call_user_func(function () use ($config) {
    $config['root_path'] = __DIR__;
    /** @remember if you run from cli the root_url will be weird looking */
    $config['root_url'] = join_path((!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . @$_SERVER['HTTP_HOST'], $config['root_uri']);

    $config['tmp_dir'] = __DIR__ . '/tmp/';

    return $config;
});

$registry = new \Resgef\SyncList\Lib\Registry\Registry();

$registry->set('config', $config);

$registry->set('request', \Symfony\Component\HttpFoundation\Request::createFromGlobals());
$registry->set('response', \Symfony\Component\HttpFoundation\Response::create('', \Symfony\Component\HttpFoundation\Response::HTTP_OK, []));

$registry->set('url', new \Resgef\SyncList\Lib\Url\Url($registry));

$registry->set('twig', call_user_func(function () use ($registry, $config) {
    $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($config['templates_dir']), ['debug' => true]);
    return $twig;
}));

$registry->set('db', new \Resgef\SyncList\Lib\Mysqli\MySQLi($config['db']['default']['host'], $config['db']['default']['user'], $config['db']['default']['password'], $config['db']['default']['database'], $config['db']['default']['prefix']));

$registry->set('livecomm', new \Resgef\SyncList\Lib\Livecommresponse\Livecommresponse());

$registry->set('model', new \Resgef\SyncList\Models\ModelHelpers\ModelHelpers($registry));

$registry->set('auth', new \Resgef\SyncList\Lib\AuthenticatedUser\AuthenticatedUser($registry->get('model')));

$app = new \Resgef\SyncList\Lib\SyncList\SyncListApp($registry);

return $app;