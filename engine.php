<?php
use Inviqa\SkypeEngine;
global $n;

$engine = new SkypeEngine($n);

$engine->add(':create', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Sure. Why not.");
});

$engine->add(':ping', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Pong!");
});

return $engine;
