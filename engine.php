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

$engine->add(':magento', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Have you tried clearing the cache?");
});

return $engine;
