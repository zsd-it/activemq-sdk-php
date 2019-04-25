# 介绍
使用了 activeMq , 这里封装了其使用方式, 与Qubs的调用封装一致

## 如何使用

通过composer引用SDK

```
composer require zsd/activemq-sdk-php
```



## 开始使用

```
<?php
$qbus = new Mq($endPoint, $userName, $password, $timeoutSeconds, $port);
```
参数：

* endPoint: Api的地址
* userName: 用户名
* password: 密码
* $timeoutSeconds : 可选参数. 这里仅仅为了与Qbus参数保持一致 ,该参数实际没有用处.
* $port: 可选参数，端口号 . 默认:61613



## 获取一个队列
```php
<?php
$queue = $qbus->getQueue($queueName);
```

参数：
* queueName：队列名称

返回值：
* 队列操作对象


## 发送消息到队列

特性：
* 支持批量发送 最大支持16条批量发送, 不推荐使用. 

```php
<?php
$msgId = $queue->sendMessage($message, $delaySeconds);
$msgId = $queue->sendBatchMessage($messages, $delaySeconds);
```

参数：
* message(s)：消息内容（列表），字符串类型
* $delaySeconds：消息延迟投递时间, 可选参数. 这里仅仅为了与Qbus参数保持一致 ,该参数实际没有用处.

返回值：
* 单条推送返回 boolean
* 批量发送: 如果有发送失败的message, 会返回$messages的 `键`(数组). 所有都成功则返回 true


## 从队列拉消息

特性：
* 支持指定时长的长连接
* 支持批量拉取消息 最大支持16条批量消费

```php
<?php
$msg = $queue->receiveMessage($pollingWaitSeconds);
// 获取消息体
if (!is_null($msg)) {
    $string = $msg->getBody();
}

$msgList = $queue->batchReceiveMessage($messageCount, $pollingWaitSeconds);

// 获取消息体
foreach($msgList as $msg) {
    $string = $msg->getBody();
}

```

参数
* pollingWaitSeconds: 长连接等待时长（秒) ,默认 3 秒 . 如果是0代表一直等待拉取.

返回值：
* 单条推送返回消息对象 | null
* 批量发送返回消息对象数组或者null.


## 确认消息

特性：
* 拉取消息后，消费端处理完后需要显式删除该消息，否则超时后，消息将会重新可见，导致重复消费。
* 支持批量确认 最大支持16条批量确认


```php
$queue->deleteMessage($message);
$queue->deleteBatchMessage($messages);
```
参数：
* message：消息对象

返回值
* 单条确认 返回 boolean
* 批量确认,如果有失败则返回对应的`键`(数组) , 否则返回 true


##获取一个主题
```php 
<?php
$topic = $qbus->getTopic($topicName);
```
参数：
* topicName：主题名称


返回值：
* 主题操作对象

## 发布消息到主题

```php

<?php
$topic->publishMessage($message);
$topic->publishBatchMessage($messages, $tagList, $routingKey, $delaySeconds);

```
参数：
* message(s)：消息内容（列表），字符串类型


返回值：
* 单条推送: 返回 boolean
* 多条推送: 返回发送失败的`键`(数组) 或者 true