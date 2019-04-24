<?php
/**
 * Created by PhpStorm.
 * User: zsd
 * Date: 2019/4/23
 * Time: 下午8:47
 */

$vendor = dirname(__FILE__) . '/../vendor/autoload.php';

require_once $vendor;

include '../src/Mq.php';
include '../src/MqClient.php';
include '../src/Queue.php';
include '../src/Topic.php';
include '../src/Exception/MqException.php';
include '../src/Exception/MqServerException.php';
include '../src/Exception/MqClientException.php';

class Test
{
    public function sendTopic()
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \MQ\Mq($endPoint, $userName, $password);

        $topic   = $mq->getTopic('sms.send');
        $message = 'test ' . date('Y-m-d H:i:s');
        $topic->publishMessage($message);
    }

    public function sendQueue()
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \Mq\Mq($endPoint, $userName, $password);

        $topic   = $mq->getQueue('sms.send');
        $message = 'test ' . date('Y-m-d H:i:s');
        $topic->sendMessage($message);
    }

    public function getQueue()
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \MQ\Mq($endPoint, $userName, $password);

        $queue   = $mq->getQueue('sms.send');
        $message = $queue->receiveMessage(10);

        $str = '';
        if ($message) {
            $str = $message->getBody();
            $queue->deleteMessage($message);
        }

        print_r($message);
        print_r($str);
    }
}

$test = new Test();
$test->sendQueue();

//$test->getQueue();
