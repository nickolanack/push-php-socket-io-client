<?php

namespace socketio;

class Client{

	private $credentials;
	private $url;

	protected $events=array();
	private $client=null;

	private $httpsBroadcast=false;

	public function __construct($url, $args){
			
		$this->url=$url;
		$this->credentials=$args;

		if(is_object($this->credentials)){
			$this->credentials=get_object_vars($this->credentials);
		}
		


	}

	public function useHttpsBroadcast(){
		$this->httpsBroadcast=true;
		return $this;
	}

	protected function getClient(){
		if(empty($this->client)){
			$client = new \ElephantIO\Client(new \ElephantIO\Engine\SocketIO\Version2X($this->url, array()));
			$client->initialize();
			$this->client=$client;
		}

		return $this->client;
	}


	public function broadcast($channel, $event, $data){


		if($this->httpsBroadcast){
			return $this->post($channel, $event, $data);
		}


		$client=$this->getClient();
		
		$client->emit('authenticate', $this->credentials);
		$client->emit('emit', array("channel"=>$channel.'/'.$event, "data"=>$data));
		//$client->close();

		return $this;

	}

	private function post($channel, $event, $data){

		$client = new \GuzzleHttp\Client();
        $httpcode = 0;
        try {
            $response = $client->request('POST', $this->url.'/emit', array(
                'timeout' => 15,
                'form_params' => array(
                    'credentials'=>json_encode($this->credentials),
                    'channel'=>$channel.'/'.$event,
                    'data'=>json_encode($data),
                ),
            ));
            $httpcode = $response->getStatusCode();

        } catch (\RequestException $e) {
            //echo $e->getRequest();
            if ($e->hasResponse()) {
                $httpcode = $e->getResponse()->getStatusCode();
            }
        }

        if ($httpcode !== 200) {
            throw new \Exception('Ajax Request Error ' . $httpcode);
        }

        //$body = $response->getBody();
        //echo $body;

        return $this;

	}


	public function __destruct()
    {
    	if($this->client){
        	$this->client->close();
    	}
    }


	public function getPresenceGroup($channels, $event){


		$client=$this->getClient();

		$client->emit('authenticate', $this->credentials);

		

		
		$emitData=array("channels"=>array_map(function($c)use($event){
			return $c.'/'.$event;
		}, $channels));
		

		$client->emit('presence', $emitData);

		while (true) {
		    $r = $client->read();

		    if (!empty($r)) {
		        $presence=explode('[', $r, 2);
		        $presence='['.array_pop($presence);
		        $presence=json_decode($presence);

		        return $presence[1];

		        break;
		    }
		}

	


		return $this;


	}


	public function getPresence($channel, $event){


		$client=$this->getClient();

		$client->emit('authenticate', $this->credentials);

		

		
		$emitData=array("channel"=>$channel.'/'.$event);
		
		$client->emit('presence', $emitData);

		while (true) {
		    $r = $client->read();

		    if (!empty($r)) {
		        $presence=explode('[', $r, 2);
		        $presence='['.array_pop($presence);
		        $presence=json_decode($presence);

		        return $presence[1];

		        break;
		    }
		}

	


		return $this;


	}


	public function on($channel, $event, $callback){

		$this->events[]=array("event"=>$channel.'/'.$event, 'callback'=>$callback);

		return $this;
	}

	public function listen(){


		$client = new \ElephantIO\Client(new \ElephantIO\Engine\SocketIO\Version2X($this->url, array()));
		$client->initialize();
		$client->emit('authenticate', $this->credentials);

		foreach ($this->events as $e) {
			$client->emit('subscribe', array($e['event']));
		}

		while (true) {
		    $r = $client->read();
		    if (!empty($r)) {
		        //var_dump($r);
		        //
		        $parts=explode('[', $r, 2);
		        $id=$parts[0];
		        $message=json_decode('['.$parts[1]);
		    
		        $event=$message[0];
		        $data=$message[1];

		        foreach ($this->events as $e) {
					if($e['event']===$event){
						$e['callback']($data);
					}
				}
		    }

		}
	}

}