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
    $suggestions = array(
        'Have you tried clearing the cache?',
        'Have you tried reindexing?',
        'Have you tried restarting memcache?',
        'Have you sacrificed a chicken?',
    );
    $engine->cmd(sprintf("CHATMESSAGE {$chatname['val']} ", $suggestions[array_rand($suggestions)]));
});

$engine->add(':burritos?', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} https://docs.google.com/a/inviqa.com/spreadsheet/ccc?key=0AgaDiKrNejnsdHRGTEFIMGxlOVVxQXpkbExYQlk1N2c");
});

$engine->add(':wiki', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $arg = explode(' ', $body['val']);
    $engine->cmd(sprintf("CHATMESSAGE {$chatname['val']} %s", "https://ibuildings.jira.com/wiki/label/PROFSERV/{$arg[1]}"));
});

return $engine;
