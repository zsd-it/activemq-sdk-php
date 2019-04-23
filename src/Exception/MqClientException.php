<?php
namespace Mq\Exception;

/**
 * Created by PhpStorm.
 * User: yangzhyo
 * Date: 2017/9/5
 * Time: 下午2:15
 */
class MqClientException extends MqException
{
    public function __construct($message, $code = -1, $data = array())
    {
        parent::__construct($message, $code, $data);
    }

    public function __toString()
    {
        return "MqClientException  " .  $this->getInfo();
    }
}
