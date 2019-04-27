<?php
include_once __DIR__ . '/vendor/autoload.php';

(new \socketio\Client(array(
	"username" => "{test-server-name}",
	"password" => "{password}",
	"appId" => "{test-app-id}",
	"namespace" => "test",
)))
->broadcast('chat', 'message', "Hello World");