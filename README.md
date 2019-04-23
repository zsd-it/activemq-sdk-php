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
$qbus = new Mq($endPoint, $userName, $password, $port);
```
参数：

* endPoint: Api的地址
* userName: 用户名
* password: 密码
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

```php
<?php
$msgId = $queue->sendMessage($message);
```

参数：
* message(s)：消息内容（列表），字符串类型

返回值：
* null


## 从队列拉消息

特性：
* 支持指定时长的长连接

```php
<?php
$msg = $queue->receiveMessage($pollingWaitSeconds);
```

参数
* pollingWaitSeconds: 长连接等待时长（秒) ,默认 3 秒

返回值：
* string | null



获取一个主题
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
$msgId = $topic->publishMessage($message);


```
参数：
* message(s)：消息内容（列表），字符串类型


返回值：
* null