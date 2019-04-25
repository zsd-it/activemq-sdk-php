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
    public function sendMessage($message, $delaySeconds = 3)
    {
        return $this->MqClient->sendToQueue($this->queueName, $message);
    }

    /**
     * 批量发送
     *
     * @param     $messages
     * @param int $delaySeconds
     *
     * @return array|null
     */
    public function sendBatchMessage($messages, $delaySeconds = 3)
    {
        if (count($messages) > 16) {
            throw new MqException('批量推送消息数,不能大于16, 当前 : ' . count($messages));
        }

        $error = [];
        foreach ($messages as $key => $item) {
            if (!$this->sendMessage($item, $delaySeconds)) {
                $error[] = $key;
            }
        }

        if (count($error) > 0) {
            return $error;
        }

        return null;
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
     * 批量拉取消息
     *
     * @param     $messageCount
     * @param int $pollingWaitSeconds
     *
     * @return array
     */
    public function batchReceiveMessage($messageCount, $pollingWaitSeconds = 3)
    {
        if ($messageCount > 16 || $messageCount < 1) {
            throw new MqException('参数 messageCount 不能小于1大于16');
        }

        $messages = [];
        for ($i = 0; $i < $messageCount; $i++) {
            $message = $this->MqClient->receiveMessage($this->queueName, $pollingWaitSeconds);
            if (!is_null($message)) {
                $messages[] = $message;
            } else {
                break;
            }
        }

        return $messages;
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
            throw new MqException('消息删除失败, 消息必须是StompMessage object');
        }
    }

    /**
     * 批量删除消息 ,返回删除失败的key
     *
     * @param $messages
     *
     * @return array | null
     */
    public function deleteBatchMessage($messages)
    {
        $errors = [];
        foreach ($messages as $key => $message) {
            if ($message instanceof StompMessage) {
                $this->MqClient->deleteMessage($this->queueName, $message);
            } else {
                $errors[] = $key;
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return null;
    }
}
