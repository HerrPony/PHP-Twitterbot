<?php

require_once('lib/chatterbotapi.php');

class Database extends SQLite3
{
    function __construct()
    {
        $this->open('/home/twitterbot/extensions/cleverbot/geldi.sqlite');
    }
}

class cleverbot {

    protected $stream;

    function onLoad($stream) {

        $this->stream = $stream;

    }

    function onEvent($data) {

        $factory = new ChatterBotFactory();
        $cleverbot = $factory->create(ChatterBotType::CLEVERBOT);
        $cleverbotsession = $cleverbot->createSession();
    
        $text = str_replace("@in0sit", "", strtolower($data->text));
       
        $wait = rand(2,5);
        sleep($wait);

        if($this->stream->stringContains($text, "#chess")) {
            $this->stream->logMessage("Text contains '#chess'. Favoriting it.");
            $this->stream->favoritTweet('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', $data->id_str);
        }

        if($this->stream->stringContains($text, "#geldi")) {
            
            $db = new Database();
            $result = $db->query("SELECT * FROM geldi WHERE id = '1'");
            $geld = $result->fetchArray();
            $count = $geld['count'] + 1;
            $db->exec("UPDATE geldi SET count = '".$count."' WHERE id = '1'");            

            $this->stream->logMessage("Text contains '#geldi'. Sending easteregg.");

            $this->stream->sendAnswer('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', '@'.$data->user->screen_name.' Habe ich da was von #Geldi gehört? '.$count.'. #GeldiTweet', $data->id_str);
            
            $wait = rand(2,5);
            sleep($wait);

            $this->stream->favoritTweet('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', $data->id_str);

            $wait = rand(2,5);
            sleep($wait);

            $this->stream->retweetTweet('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', $data->id_str); 

        } else {

            $this->stream->logMessage("Sending '".$text."' to Cleverbot");

            $result = $cleverbotsession->think($text);
        
            $this->stream->logMessage("Recieved answer. Sending '".$result."' to user");
        
            $this->stream->sendAnswer('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', '@'.$data->user->screen_name.' '.$result, $data->id_str);
        }

    }

}


?>
