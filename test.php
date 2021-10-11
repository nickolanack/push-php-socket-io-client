<?php
include_once __DIR__ . '/vendor/autoload.php';

$credentials=json_decode(file_get_contents(__DIR__.'/credentials.json'));
(new \socketio\Client($credentials->url, $credentials))
->useHttpsBroadcast()
->broadcast('test', 'test', array('hello', 'world'))
->getPresenceGroup(array('user.1', 'user.2'),'notifications');