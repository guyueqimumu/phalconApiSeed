<?php

namespace Application\Core\Components\Logger;

use Phalcon\Logger\Adapter\File as adapterFile;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * Created by QiLin.
 * User: NO.01
 * Date: 2020/1/17
 * Time: 11:40
 */
class Manger extends adapterFile
{
    public function __construct(string $name, ?array $options = null)
    {
        parent::__construct($name, $options);
        $formatter = new LineFormatter("[%date%][%type%]%message%");
        $formatter->setDateFormat("Y-m-d H:i:s");
        $this->setFormatter($formatter);
    }
}