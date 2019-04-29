<?php
include_once __DIR__ . '/vendor/autoload.php';

(new \socketio\Client('{socket-io-url}', json_decode(file_get_contents(__DIR__.'/credentials.json'))))
->broadcast('chat', 'message', "Hello World");