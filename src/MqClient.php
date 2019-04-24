<?php

namespace Mq;

use Enqueue\Stomp\StompConnectionFactory;
use Enqueue\Stomp\StompMessage;
use Mq\Exception\MqException;

class MqClient
{
    private $host;
    private $port;
    private $userName;
    private $password;
    private $timeoutSeconds;
    private $version = MQ_SDK_VERSION;

    protected $factory;

    /** @var \Enqueue\Stomp\StompContext $context */
    protected $context;

    /**
     * MqClient constructor.
     *
     * @param $host
     * @param $port
     * @param $userName
     * @param $password
     * @param $timeoutSeconds
     */
    public function __construct($host, $port, $userName, $password, $timeoutSeconds)
    {
        $this->host           = $host;
        $this->port           = $port;
        $this->userName       = $userName;
        $this->password       = $password;
        $this->timeoutSeconds = $timeoutSeconds;
        $this->context        = $this->getConnectionFactory()->createContext();
    }

    //===============================================stomp operation===============================================

    /**
     * @return \Enqueue\Stomp\StompConnectionFactory
     */
    protected function getConnectionFactory()
    {
        if (!isset($this->factory)) {
            $this->factory = new StompConnectionFactory([
                'host'     => $this->host,
                'port'     => $this->port,
                'login'    => $this->userName,
                'password' => $this->password,
            ]);
        }

        return $this->factory;
    }

    /**
     * @return \Enqueue\Stomp\StompContext
     */
    protected function getContext()
    {
        return $this->getConnectionFactory()->createContext();
    }

    //===============================================queue operation===============================================

    /**
     * 发送消息
     *
     * @param $name
     * @param $message
     *
     * @return bool
     */
    public function sendToQueue($name, $message)
    {
        try {
            $name = '/queue/' . $name;
            $this->context->createProducer()->send(
                $this->context->createQueue($name),
                $this->context->createMessage($message)
            );
        } catch (\Exception $exception) {
            throw  new MqException($exception->getMessage());
        }

        return true;
    }

    /**
     * 接收消息
     *
     * @param $topicName
     *
     * @return \Enqueue\Stomp\StompMessage | null
     */
    public function receiveMessage($topicName, $pollingWaitSeconds)
    {
        $consumer = $this->context->createConsumer($this->context->createQueue($topicName));
        $message  = $consumer->receive($pollingWaitSeconds);

        return $message;
    }

    /**
     * 删除消息
     *
     * @param                             $topicName
     * @param \Enqueue\Stomp\StompMessage $message
     */
    public function deleteMessage($topicName, StompMessage $message)
    {
        $consumer = $this->context->createConsumer($this->context->createQueue($topicName));
        //确认处理消息
        $consumer->acknowledge($message);
    }

    //=============================================topic operation================================================

    /**
     * 发送到 topic 消息
     *
     * @param $foo
     * @param $message
     *
     * @return bool
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\InvalidDestinationException
     * @throws \Interop\Queue\InvalidMessageException
     */
    public function sendToTopic($name, $message)
    {
        try {
            $name = '/topic/' . $name;
            $this->context->createProducer()->send(
                $this->context->createTopic($name),
                $this->context->createMessage($message)
            );
        } catch (\Exception $exception) {
            throw  new MqException($exception->getMessage());
        }

        return true;
    }
}
