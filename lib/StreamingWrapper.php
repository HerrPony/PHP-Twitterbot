<?php

require_once('lib/Phirehose.php');
require_once('lib/twitteroauth.php');

class Stream extends Phirehose {
    
    protected $keywords;
    protected $mystream;
    protected $extensions;    

    public function registerEvent($event, $function=null) {
        $tmp[0] = $event;
        $tmp[1] = !is_null($function) ? $function : "event_".$event;
        $this->keywords[] = $tmp;
	$this->logMessage("Registred event '".$event."' with function '".$tmp[1]."'.");
    }

    public function registerExtensionEvent($event, $extension) {
        $tmp[0] = $event;
        $tmp[1] = $extension;
        $this->extensions[] = $tmp;
	$this->logMessage("Registred event '".$event."' with extension '".$extension."'.");
    }
    
    public function enqueueStatus($status) {
        $data = json_decode($status);

        if(!@is_object($data->retweeted_status)) {
            if(is_array($this->keywords)) {
	            foreach($this->keywords as $function) {
		            if($this->stringContains($data->text, $function[0])) {
				        $this->logMessage("Event '".$function[0]."' fired. Launching function '".$function[1]."'.");
			            call_user_func($function[1], $data);
                        $this->logMessage("Finished function call.");
		            }
	            }
            }

            if(is_array($this->extensions)) {
                foreach($this->extensions as $extension) {
                    if($this->stringContains($data->text, $extension[0])) {
			            $this->logMessage("Event '".$extension[0]."' fired. Loading extension '".$extension[1]."'.");
                        include_once('extensions/'.$extension[1].'/index.php');
                        $ext = new $extension[1]();
                        $ext->onLoad($this);
                        $ext->onEvent($data);
                        unset($ext);
                        $this->logMessage("Finished extension call.");
                    }
                }
            }
        }

    }
    
    public function run() {
        
	$this->logMessage("Streaming started ...");
	
        $track = array();
        if(is_array($this->keywords)) {
            foreach($this->keywords as $keyword) {
                $track[] = $keyword[0];
            }
        }

        if(is_array($this->extensions)) {
            foreach($this->extensions as $keyword) {
                $track[] = $keyword[0];
            }
        }
        
	$this->logMessage("Waiting for events with following keywords:");
	foreach($track as $keyword) {
	    $this->logMessage(" - ".$keyword);
	}
	$this->logMessage("");
	
        $this->setTrack($track);
        $this->consume();
    }

    public function sendTweet($consumer_key, $consumer_secret, $access_key, $access_secret, $message) {
        $twitter = new TwitterOAuth ($consumer_key, $consumer_secret, $access_key, $access_secret);
        $twitter->post('statuses/update', array('status' => $message));
    }

    public function sendAnswer($consumer_key, $consumer_secret, $access_key, $access_secret, $message, $to) {
        $this->logMessage("Sending message '".$message."' to '".$to."'");
        $twitter = new TwitterOAuth ($consumer_key, $consumer_secret, $access_key, $access_secret);
        $twitter->post('statuses/update', array('in_reply_to_status_id' => $to, 'status' => $message));
    }

    public function favoritTweet($consumer_key, $consumer_secret, $access_key, $access_secret, $id) {
        $twitter = new TwitterOAuth ($consumer_key, $consumer_secret, $access_key, $access_secret);
        $twitter->post('favorites/create/'.$id);
    }
   
    public function retweetTweet($consumer_key, $consumer_secret, $access_key, $access_secret, $id) {
        $twitter = new TwitterOAuth ($consumer_key, $consumer_secret, $access_key, $access_secret);
        $twitter->post('statuses/retweet/'.$id);
    }
 
    public function logMessage($message, $level=0) {
	    switch($level) {
	        case 0:
		        echo "[TWITTERBOT]: [INFO] ".$message."\n";
		        break;
	        case 1:
		        echo "[TWITTERBOT]: [WARNING] ".$message."\n";
		        break;
	        case 2:
		        echo "[TWITTERBOT]: [ERROR] ".$message."\n";
		        break;
	        case 3:
		        echo "[TWITTERBOT]: [FATAL ERROR] ".$message."\n";
		        die();
		    break;
	    }
    }

    function stringContains($string, $keyword) {

	    $pos = strpos(strtolower($string), strtolower($keyword));
	    if($pos === false) {
		    return false;
	    } else {
		    return true;
	    }

    }
    
}
