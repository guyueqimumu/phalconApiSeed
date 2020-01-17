<?php
/**
 * Created by QiLin.
 * User: NO.01
 * Date: 2020/1/17
 * Time: 15:12
 */

use Phalcon\Queue\Beanstalk;

$app->get('/Test',function ()use ($app){
    $app->queue->put([
        'data'=>'你好'
    ]);
});
$app->get('/Test/get',function ()use ($app){
    while (($job = $app->queue->peekReady()) !== false) {
        $message = $job->getBody();
        print_r($message);
        $job->delete();
    }
});