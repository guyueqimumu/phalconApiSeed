<?php
/**
 * Created by QiLin.
 * User: NO.01
 * Date: 2020/1/16
 * Time: 15:22
 */
return new \Phalcon\Config([
    'logger' => [
        'file' => ROOT_PATH . DIRECTORY_SEPARATOR . "Logs" . DIRECTORY_SEPARATOR
    ],
    'queue' => [
        'host' => 'beanstalkd',
        'port' => '11300',
        'chooseTube' => 'default',
    ],
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'test',
        'charset' => 'utf8',
    ],
    'application' => [
        'modelsDir' => APP_PATH . '/models/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'viewsDir' => APP_PATH . '/views/',
        'baseUri' => 'http://inini.cn/',
    ],
    'allowOriginOptions' => ['rongbaojie.cn', 'freeradio.cn']
]);
