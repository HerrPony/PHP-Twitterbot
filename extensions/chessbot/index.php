<?php

class chessbot {
    
    protected $stream;
    
    function onLoad($stream) {
        $this->stream = $stream;
    }
    
    function onEvent($data) {
        if(strpos($data->text, "#bottest") !== false) {
            $this->stream->sendTweet('UJMQVGmLJLBreYmpXpMwjw', 'wbtnPI0hQIP9DD1bfwiibRz0IStetOKRNBA7LNR4', '572709203-pyH5b6aGO3WWbuX2vpZanLmSs5awvkDqmRxUwTr1', 'Oo7PcFjDM2XYV5l4ZH3d35O9nN2AeCPUlP8gdokqis', '@'.$data->user->screen_name.' CHESS!');
        }
    }
    
}

?>