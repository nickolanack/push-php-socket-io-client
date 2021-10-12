<?php
if(file_exists(__DIR__ . '/vendor/autoload.php')){
	include_once __DIR__ . '/vendor/autoload.php';
}else{
	require __DIR__ . '/../../autoload.php';
}

$credentials=json_decode(file_get_contents(__DIR__.'/credentials.json'));
$socket=(new \socketio\Client($credentials->url, $credentials))
	->useHttpsBroadcast();
$socket->broadcast('test', 'test', array('hello', 'world'));
$socket->getPresenceGroup(array('user.1', 'user.2'),'notifications');
$socket->getPresence('user.1','notifications');