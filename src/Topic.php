<?php

namespace Mq;

use Mq\Exception\MqException;

class Topic
{
    private $topicName;

    /** @var \Mq\MqClient $MqClient */
    private $MqClient;

    public function __construct($topicName, $cmqClient)
    {
        $this->topicName = $topicName;
        $this->MqClient  = $cmqClient;
    }

    /**
     * 推送消息 , 内部参数为了与Qubs保持一致,添加了些无效参数
     *
     * @param      $message
     * @param null $tagList
     * @param null $routingKey
     * @param null $delaySeconds
     *
     * @return bool
     */
    public function publishMessage($message, $tagList = null, $routingKey = null, $delaySeconds = null)
    {
        return $this->MqClient->sendToTopic($this->topicName, $message);
    }

    public function publishBatchMessage($messages, $tagList = null, $routingKey = null, $delaySeconds = null)
    {
        if (count($messages) > 16) {
            throw new MqException('推送消息数超过了16个');
        }

        $error = [];
        foreach ($messages as $key => $message) {
            if (!$this->MqClient->sendToTopic($this->topicName, $message)) {
                $error[] = $key;
            }
        }
        if (count($error) > 0) {
            return $error;
        }

        return null;
    }
}
