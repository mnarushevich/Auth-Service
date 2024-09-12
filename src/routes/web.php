<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

use Illuminate\Support\Facades\Route;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/test', function () {
    $host = 'rabbitmq-exam.rmq3.cloudamqp.com';
    $exchangeName = 'exchange.b5e6f709-16fb-49f0-ace0-16378e4632a8';
    $routingKey = 'b5e6f709-16fb-49f0-ace0-16378e4632a8';
    $queName = 'exam';
    $connection = new AMQPStreamConnection($host, 5672, 'student', 'XYR4yqc.cxh4zug6vje', 'mxifnklj');
    $channel = $connection->channel();

    $channel->exchange_declare($exchangeName, 'direct', false, true, false);

    $msg = new AMQPMessage(
        'Hi CloudAMQP, this was fun!',
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT],
    );

    $channel->queue_bind($queName, $exchangeName, $routingKey);
    $channel->basic_publish($msg, $exchangeName, $routingKey);

    echo ' [x] Sent \'Hi CloudAMQP, this was fun!', "\n";

    $channel->close();
    $connection->close();
});
