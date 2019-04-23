<?php
namespace Mq\Exception;

/**
 * Created by PhpStorm.
 * User: yangzhyo
 * Date: 2017/9/5
 * Time: 下午2:17
 */
class MqServerException extends MqException
{
    public $requestId;
    public function __construct($message, $code = -1, $data = array(), $requestId = null)
    {
        parent::__construct($message, $code, $data);
        $this->requestId = $requestId;
    }

    public function __toString()
    {
        return "MqServerException  " .  $this->getInfo() . ", RequestID:" . $this->requestId;
    }
}
