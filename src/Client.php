<?php

namespace socketio;

class Client{

	private $credentials;
	private $url;

	public function __construct($url, $args){
			
		$this->url=$url;
		$this->credentials=$args;

		if(is_object($this->credentials)){
			$this->credentials=get_object_vars($this->credentials);
		}
		


	}


	public function broadcast($channel, $event, $data){

		$client = new \ElephantIO\Client(new \ElephantIO\Engine\SocketIO\Version2X($this->url, array()));
		$client->initialize();
		$client->emit('authenticate', $this->credentials);
		$client->emit('emit', array("channel"=>$channel.'/'.$event, "data"=>$data));
		$client->close();

	}


}