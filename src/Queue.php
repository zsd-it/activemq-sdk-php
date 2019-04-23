<?php

namespace Mq;

class Queue
{
    private $queueName;

    /** @var \Mq\MqClient Mq客户端 */
    private $MqClient;

    /**
     * Queue constructor.
     *
     * @param $queueName
     * @param $MqClient
     */
    public function __construct($queueName, $MqClient)
    {
        $this->queueName = $queueName;
        $this->MqClient  = $MqClient;
    }

    /**
     * @param     $message
     * @param int $delaySeconds
     *
     * @return boolean
     */
    public function sendMessage($message)
    {
        return $this->MqClient->sendToQueue($this->queueName, $message);
    }

    /**
     * 拉取队列消息
     *
     * @param int $pollingWaitSeconds 等待超时时间,默认3秒
     *
     * @return null|string
     */
    public function receiveMessage($pollingWaitSeconds = 3)
    {
        $message = $this->MqClient->receiveMessage($this->queueName, $pollingWaitSeconds);

        return is_null($message) ? null : $message->getBody();
    }
}
