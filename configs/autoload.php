<?php
/**
 * Created by QiLin.
 * User: NO.01
 * Date: 2020/1/17
 * Time: 10:45
 */

spl_autoload_register(function ($class) {
    $nameSpace = explode('\\', $class);
    $className = array_pop($nameSpace);
    if (array_shift($nameSpace) === 'Application') {
        $nameSpace = array_map("lcfirst", $nameSpace);
        $filename = ROOT_PATH . DIRECTORY_SEPARATOR . (implode('/', $nameSpace) . DIRECTORY_SEPARATOR . $className . ".php");
        if (file_exists($filename)) {
            include_once $filename;
        }
    }
});