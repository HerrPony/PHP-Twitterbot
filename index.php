<?php

require_once('lib/StreamingWrapper.php');

$stream = new Stream('In0sit', 'tribes123', Stream::METHOD_FILTER);
$stream->registerExtensionEvent("@in0sit", "cleverbot");
//$stream->registerExtensionEvent("#chess", "chessbot");
$stream->run();
