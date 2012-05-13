<?php

require_once('lib/StreamingWrapper.php');

$stream = new Stream('xxx', 'xxx', Stream::METHOD_FILTER);
$stream->registerExtensionEvent("@in0sit", "cleverbot");
//$stream->registerExtensionEvent("#chess", "chessbot");
$stream->run();
