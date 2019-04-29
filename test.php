<?php
include_once __DIR__ . '/vendor/autoload.php';

$credentials=json_decode(file_get_contents(__DIR__.'/credentials.json'));
(new \socketio\Client($credentials->url, $credentials))
->broadcast('chat', 'message', "Hello World");