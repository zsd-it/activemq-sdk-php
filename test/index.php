<?php
/**
 * Created by PhpStorm.
 * User: zsd
 * Date: 2019/4/23
 * Time: 下午8:47
 */

$dir    = dirname(__FILE__);
$vendor = $dir . '/../vendor/autoload.php';

require_once $vendor;

include $dir . '/../src/Mq.php';
include $dir . '/../src/MqClient.php';
include $dir . '/../src/Queue.php';
include $dir . '/../src/Topic.php';
include $dir . '/../src/Exception/MqException.php';
include $dir . '/../src/Exception/MqServerException.php';
include $dir . '/../src/Exception/MqClientException.php';

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

    public function batchSendTopic($count)
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \MQ\Mq($endPoint, $userName, $password);

        $topic   = $mq->getTopic('sms.send');
        for ($i = 0; $i < $count; $i++) {
            $messages[] = 'test ' . date('Y-m-d H:i:s');
        }
        var_dump($topic->publishBatchMessage($messages));
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

    public function batchSendQueue($count)
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \Mq\Mq($endPoint, $userName, $password);

        $topic    = $mq->getQueue('sms.send');
        $messages = [];
        for ($i = 0; $i < $count; $i++) {
            $messages[] = 'test ' . date('Y-m-d H:i:s');
        }

        $topic->sendBatchMessage($messages);
    }

    public function getQueue()
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \MQ\Mq($endPoint, $userName, $password);

        $queue   = $mq->getQueue('sms.send');
        $message = $queue->receiveMessage(0);

        $str = '';
        if ($message) {
            $str = $message->getBody();
            $queue->deleteMessage($message);
        }

        print_r($message);
        print_r($str);
    }

    public function getBatchQueue($count)
    {
        $endPoint = 'localhost';
        $userName = 'admin';
        $password = 'admin';
        $mq       = new \MQ\Mq($endPoint, $userName, $password);

        $queue    = $mq->getQueue('sms.send');
        $messages = $queue->batchReceiveMessage($count);

        $str = [];
        foreach ($messages as $message) {
            $str[] = $message->getBody();
        }

        $queue->deleteBatchMessage($messages);

        print_r($str);
        print_r($messages);
    }
}

$test = new Test();
//$test->sendQueue();

$test->batchSendTopic(5);

//$test->getQueue();

//$test->batchSendQueue(16);

//$test->getBatchQueue(5);

