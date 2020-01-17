<?php

namespace Application\Core\Components\Internet\Http;

use Phalcon\DI\InjectionAwareInterface;
use Phalcon\DiInterface;

/**
 * 包装response的数据
 * Class ApiResponse
 * @package  Application\Core\Components\Internet
 */
class Response implements InjectionAwareInterface
{

    /**
     * HTTP CODE
     * @var int
     */
    public $httpCode = 200;

    /**
     * Bad Request
     */
    const HTTP_BAD_REQUEST_CODE = 400;

    /**
     * Not Found
     */
    const HTTP_NOT_FOUND_CODE = 404;

    /**
     * ok
     */
    const HTTP_OK_CODE = 200;

    /**
     *  Internal Server Error
     */
    const HTTP_INTERNAL_SERVER_ERROR_CODE = 506;
    //    const HTTP_INTERNAL_SERVER_ERROR_CODE = 200;

    /**
     * Unauthorized
     */
    const HTTP_UNAUTHORIZED_CODE = 401;

    /**
     * No Content
     */
    const HTTP_NO_CONTENT_CODE = 204;
    /**
     * 被禁止
     */
    const FORBIDDEN_HTTP_CODE = 403;
    /**
     * 没有内容
     */
    const NOT_CONTENT_HTTP_CODE = 204;

    /**
     * @var string
     */
    private $responseType = '';

    protected $_di;

    public function setDI(DiInterface $di)
    {
        $this->_di = $di;
    }

    public function getDi()
    {
        return $this->_di;
    }

    /**
     * 设置http code
     * @param $code
     */
    public function setHttpCode($code)
    {
        $this->httpCode = $code;
    }

    /**
     * 获取http code
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * 输出数据
     * @param $data
     * @param int $code
     * @param  $message
     * @return array
     */
    public function success($data, $code = self::HTTP_OK_CODE, $message = '')
    {
        $this->responseType = 'success';
        $this->setHttpCode($code);
        $responseData = [
            "data" => $data,
            "message" => $message,
            "status" => "success",
            "code" => (string)$code,
        ];
        $this->debug($responseData);

        return $responseData;
    }


    /**
     * Author:Robert
     *
     * @param $responseData
     */
    protected function debug($responseData)
    {
        $request = new \Phalcon\Http\Request();
        $requestHeaders = $request->getHeaders();
        $method = '';
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']) {
            $method = $_SERVER['REQUEST_METHOD'];
        }
        $requestUri = $method . ' ' . $_SERVER['REQUEST_URI'];
        $logger = $this->getDi()->get('logger');
        if ($requestHeaders) {
            $logger->debug($requestUri . ' HEADER ' . json_encode($requestHeaders));
        }
        $methods = ['POST', 'PUT', 'DELETE'];
        if (in_array($method, $methods)) {
            $logger->debug($requestUri . " REQUEST $method " . json_encode($_POST));
        }
        $logger->debug($requestUri . ' RESPONSE ' . $this->responseType . ' ' . json_encode($responseData));
    }

    /**
     * 错误输出
     * @param string $message
     * @param $code
     * @param string $exception
     * @return array
     */
    public function error($message, $code = self::HTTP_INTERNAL_SERVER_ERROR_CODE, $exception = "")
    {
        $this->responseType = 'error';
        if ($code < 10000) {
            $this->setHttpCode($code);
        } else {
            $this->setHttpCode(self::HTTP_OK_CODE);
        }
        $responseData = [
            "data" => $exception,
            "message" => $message,
            "status" => "error",
            "code" => (string)$code,
        ];
        $this->debug($responseData);
        return $responseData;
    }


    /**
     * 生成一一个请求序列
     * Author:Robert
     *
     * @return string
     */
    public static function generateSequence(): string
    {
        return sha1(uniqid(true));
    }

    /**
     * 设置header
     * Author:Robert
     * @param  $key
     * @param  $value
     * @return null
     */
    public function setHeader($key, $value = '')
    {
        if ($value === '') {
            header($key);
        } else {
            header("$key: $value");
        }
    }
}
