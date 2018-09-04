<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

$config_crawler = include 'crawler/config.crawler.php';

return new \Phalcon\Config([
    'version' => '1.0',
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'zz',
        'password' => 'ggl2018@nj',
        'dbname' => 'rocket',
        'charset' => 'utf8',
    ],
    'application' => [
        'appDir' => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir' => APP_PATH . '/models/',
        'tasksDir' => APP_PATH . '/tasks/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'viewsDir' => APP_PATH . '/views/',
        'pluginsDir' => APP_PATH . '/plugins/',
        'libraryDir' => APP_PATH . '/library/',
        'cacheDir' => BASE_PATH . '/var/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        // 'baseUri' => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
        'baseUri' => '/',
    ],
    'services' => [
        'volt.adapter' => 'Rocket\Volt\VoltAdapter',
    ],
    'volt' => [
        'extensions' => ['Rocket\Volt\CommonVoltExtension'],
    ],
    'crawler' => $config_crawler,
    'logger' => [
        'db' => BASE_PATH . '/var/logs/db.log',
    ],
    'api' => [
        'baseUrl' => '',
    ],
]);
