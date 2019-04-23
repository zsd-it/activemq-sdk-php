<?php

namespace Mq;

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

    public function publishMessage($message)
    {
        return $this->MqClient->sendToTopic($this->topicName, $message);
    }
}
