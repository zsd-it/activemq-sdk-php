<?php

namespace Mq\Exception;

use RuntimeException;

/**
 * Class MqException
 *
 * @package Mq\Exception
 */
class MqException extends RuntimeException
{
    /*
    @type code: int
    @param code: 错误类型

    @type message: string
    @param message: 错误描述

    @type data: array
    @param data: 错误数据
    */

    public $code;
    public $message;
    public $data;

    public function __construct($message, $code = -1, $data = [], $previousException = null)
    {
        parent::__construct($message, $code, $previousException);
        $this->code    = $code;
        $this->message = $message;
        $this->data    = $data;
    }

    public function __toString()
    {
        return "Mq Exception\n" . $this->getInfo();
    }

    public function getInfo()
    {
        $info = [
            "code"    => $this->code,
            "data"    => $this->data,
            "message" => $this->message
        ];

        return json_encode($info);
    }
}
