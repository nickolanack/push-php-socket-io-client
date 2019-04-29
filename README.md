# php-socket-io-client
socketio client wrapper

This is a php 'push event' emitter for use with a customized socketio server. see https://github.com/nickolanack/push-socket-io-server
which allows (js browser clients) to subscribe to event channels


#Install

Install push-socket-io-server first.

```bash
	
	git clone https://github.com/nickolanack/push-socket-io-server.git
	cd push-socket-io-server
	composer install
	#change to match your credentials
	echo '{"url":"https://your-socket-io-server", "username" : "someusername", "password" : "somepassword", "appId" : "someappid"}' | credentials.json

```

Test
```bash

	php test.php 

```

#Usage

```php


	include_once __DIR__ . '/vendor/autoload.php';

	$credentials=json_decode(file_get_contents(__DIR__.'/credentials.json'));
	(new \socketio\Client($credentials->url, $credentials))
	->broadcast('chat', 'message', "Hello World");

```