<?php


use Application\Core\Components\Logger\Manger as loggerManger;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Queue\Beanstalk;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('env', function () {
    return (isset($_SERVER['SITE_ENV']) && $_SERVER['SITE_ENV']) ? strtolower($_SERVER['SITE_ENV']) : 'prod';
});

$di->setShared('logger', function () use ($config) {
    return new loggerManger($config->logger->file);
});


$di->setShared('apiResponse', function () {
    return new  Application\Core\Components\Internet\Http\Response();
});

$di->setShared('queue', function () use ($config) {
    $options = [
        'host' => $config->queue->host,
        'port' => $config->queue->port,
        'persistent' => true,
    ];
    $queue = new Beanstalk($options);
    $queue->choose($config->queue->chooseTube);

    /**
     *
     *
     *<code>
     * $queue->put(
     *       [
     *           'processVideo' => 4871
     *        ],
     *        [
     *           'priority' => 250,//优先级 ，他是一个2^32的整数，较小的值将优先于较大的值之前工作，最紧急情况为0，最后执行4294967295
     *           'delay'    => 10,//延迟操作
     *           'ttr'      => 3600 // 允许工作允许的秒数
     *         ]
     *    );
     * </code>
     *
     *
     */

    return $queue;
});

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
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
        'charset' => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

