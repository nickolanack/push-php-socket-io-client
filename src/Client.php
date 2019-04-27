<?php

namespace socketio;

class Client{

	private $credentials;

	public function __construct($args){
			

		$this->credentials=$args;

		


	}


	public function broadcast($channel, $event, $data){

		$client = new \ElephantIO\Client(new \ElephantIO\Engine\SocketIO\Version2X('https://socketio.nickolanack.com', array()));
		$client->initialize();
		$client->emit('authenticate', $this->credentials);
		$client->emit('emit', array("channel"=>$channel.'/'.$event, "data"=>$data));
		$client->close();

	}


}