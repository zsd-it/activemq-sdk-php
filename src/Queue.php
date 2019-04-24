<?php

namespace Mq;

use Enqueue\Stomp\StompMessage;
use Mq\Exception\MqException;

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
     * @return null|\Enqueue\Stomp\StompMessage
     */
    public function receiveMessage($pollingWaitSeconds = 3)
    {
        $message = $this->MqClient->receiveMessage($this->queueName, $pollingWaitSeconds);

        return $message;
    }

    /**
     * 删除消息
     *
     * @param \Enqueue\Stomp\StompMessage $message
     */
    public function deleteMessage($message)
    {
        if ($message instanceof StompMessage) {
            $this->MqClient->deleteMessage($this->queueName, $message);
        } else {
            throw new MqException('消息格式不正确');
        }
    }
}
