<?php
/**
 * Created by QiLin.
 * User: NO.01
 * Date: 2020/1/16
 * Time: 18:34
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

error_reporting(E_ALL);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

try {
    include ROOT_PATH . '/configs/autoload.php';

    /**
     * Switch the configuration
     */
    $env = isset($_ENV['SITE_ENV']) ? strtolower($_ENV['SITE_ENV']) : 'prod';

    /**
     * Read the configuration
     */
    if ($env === 'dev') {
        $config = include ROOT_PATH . DIRECTORY_SEPARATOR . "configs/{$env}.php";
    } else {
        $config = include ROOT_PATH . "configs/{$env}.php";
    }

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */
    include ROOT_PATH . '/configs/services.php';

    /**
     * Include  application Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Include Autoloader
     */
    include ROOT_PATH . '/configs/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include APP_PATH . '/config/route.php';

    /**
     * Handle the request
     */
    $app->handle();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
