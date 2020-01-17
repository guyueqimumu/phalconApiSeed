<?php


$getAllowOrigin = function () use ($app,$config) {
    $request = (new  Phalcon\Http\Request())->getHeaders();
    if (isset($request['Origin']) and $config->allowOriginOptions) {
        $domain = explode('.', $request['Origin']);
        $currentDomain = implode('.', array_slice($domain, -2, 2));
        $configDomain = $config->allowOriginOptions->toArray();
        if (in_array($currentDomain, $configDomain)) {
            return $request['Origin'];
        }
    }
    if ($app->env == 'dev') {
        return $request['Origin'] ?? '*';
    }
    return "*";
};

$setHeader = function () use ($app,$getAllowOrigin) {
    $app->response->setHeader("Access-Control-Allow-Origin", $getAllowOrigin());
    $app->response->setHeader("Access-Control-Allow-Credentials", 'true');
    $app->response->setHeader("Content-type", "text/html; charset=utf-8");
    $app->response->setHeader("Access-Control-Allow-Methods", 'GET, POST, OPTIONS, PUT, DELETE');
    $app->response->setHeader("Access-Control-Allow-Headers", 'Access-Token,Content-Type,Access-Control-Request-Method');
};

/**
 * 根
 */
$app->get('/', function () use ($app) {
    return $app->apiResponse->success(['version' => '1.0.0', 'timestamp' => time()]);
});

/**
 * 未找到
 */
$app->notFound(function () use ($app,$setHeader) {
    $setHeader();
    if ($app->request->isOptions()) {
        $app->response->setStatusCode('200', "OK");
    } else {
        $app->response->setStatusCode(Application\Core\Components\Internet\Http\Response::HTTP_NOT_FOUND_CODE, "Not Found");
        $app->response->setJsonContent($app->apiResponse->error("Api Not Found ",404));
    }
    $app->response->send();
});

/**
 * 请求后执行
 */
$app->after(function () use ($app, $setHeader) {
    $setHeader();
    $returnValue = $app->getReturnedValue();
    if (is_array($returnValue)) {
        $app->response->setJsonContent($returnValue);
        $jsonp = $app->request->get("callback");
        if ($jsonp) {
            $app->response->setContent($jsonp . '(' . $app->response->getContent() . ')');
        }
    } else {
        $app->response->setContent($returnValue);
    }
    $app->response->send();
});
