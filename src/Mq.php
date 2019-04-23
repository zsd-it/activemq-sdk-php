<?php

namespace MQ;

define('MQ_SDK_VERSION', '0.1.0');

class Mq
{
    private $MqClient;

    /**
     * Mq constructor.
     *
     * @param     $endPoint       string MqApi地址
     * @param     $userName       string 用户名
     * @param     $password       string 用户password
     * @param int $timeoutSeconds 请求MqApi超时时间，默认为1秒
     */
    public function __construct($endPoint, $userName, $password, $port = 61613)
    {
        $this->MqClient = new MqClient($endPoint, $port, $userName, $password, $timeoutSeconds = 3);
    }

    /**
     * 获取队列操作对象
     *
     * @param $queueName string 队列名称
     *
     * @return Queue 队列操作对象
     */
    public function getQueue($queueName)
    {
        return new Queue($queueName, $this->MqClient);
    }

    /**
     * 获取主题操作对象
     *
     * @param $topicName string 主题名称
     *
     * @return Topic 主题操作对象
     */
    public function getTopic($topicName)
    {
        return new Topic($topicName, $this->MqClient);
    }
}
