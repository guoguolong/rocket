<?php

use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Rocket\Volt\VoltEngine;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {

    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

// 从配置文件中加载service
$config = $di->getConfig();
if (!empty($config->services)) {
    foreach ($config->services as $identifier => $service_class) {
        $di->setShared($identifier, function () use ($di, $service_class) {
            $srv = new $service_class($di);
            return $srv;
        });
    }
}

// $di->set(
//     'dispatcher',
//     function () use ($di) {
//         $evManager = $di->getShared('eventsManager');
//         $evManager->attach(
//             "dispatch:beforeException",
//             function ($event, $dispatcher, $exception) {
//                 switch ($exception->getCode()) {
//                     case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
//                     case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
//                         $dispatcher->forward(
//                             [
//                                 'controller' => 'error',
//                                 'action' => 'show404',
//                             ]
//                         );
//                         return false;
//                 }
//             }
//         );
//         $dispatcher = new PhDispatcher();
//         $dispatcher->setEventsManager($evManager);
//         return $dispatcher;
//     },
//     true
// );

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
            ]);
            if (!empty($config['volt']['extensions'])) {
                foreach ($config['volt']['extensions'] as $extension_class_name) {
                    $di->get('volt.adapter')->register($volt, new $extension_class_name($di));
                }
            }

            return $volt;
        },
        '.phtml' => PhpEngine::class,

    ]);
    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset,
    ];

    if ('Postgresql' == $config->database->adapter) {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
