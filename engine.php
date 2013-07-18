<?php

use Inviqa\Command\ChatMessageCommandHandler;
use Inviqa\Command\UserCommandHandler;
use Inviqa\SkypeEngine;
global $n;

$engine = new SkypeEngine($n);
$engine->addCommandHandler(new UserCommandHandler());
$chatMessageHandler = new ChatMessageCommandHandler();
$engine->addCommandHandler($chatMessageHandler);

$chatMessageHandler->add(':create', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->invoke("CHATMESSAGE {$chatname['val']} Sure. Why not.");
});

$chatMessageHandler->add(':ping', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->invoke("CHATMESSAGE {$chatname['val']} Pong!");
});

$engine->add(':magento', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $suggestions = array(
        'Have you tried clearing the cache?',
        'Have you tried reindexing?',
        'Have you tried restarting memcache or clearing redis?',
        'Has Magento fallen back to filesystem cache in the system /tmp directory?',
        'Is it a case-sensitivity issue?',
        'Is there a conflicting module over-ride?',
        'Is the layout XML picking up the correct block?',
        'Is the template getting the right data from the block?',
        'Is a store-scope configuration setting over-riding the default setting?',
        'Have you sacrificed a chicken?',
    );
    $engine->invoke(sprintf("CHATMESSAGE {$chatname['val']} %s", $suggestions[array_rand($suggestions)]));
});

$chatMessageHandler->add(':burritos?', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->invoke("CHATMESSAGE {$chatname['val']} https://docs.google.com/a/inviqa.com/spreadsheet/ccc?key=0AgaDiKrNejnsdHRGTEFIMGxlOVVxQXpkbExYQlk1N2c");
});

$chatMessageHandler->add(':planner', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->invoke("CHATMESSAGE {$chatname['val']} https://docs.google.com/a/inviqa.com/spreadsheet/ccc?key=0AvKTHYI2dY1HdG1FakNCTE9IbWtVZDZGX2RDYkp5Q0E#gid=41");
});

$chatMessageHandler->add(':wiki', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $arg = explode(' ', $body['val']);
    $engine->invoke(sprintf("CHATMESSAGE {$chatname['val']} %s", "https://ibuildings.jira.com/wiki/label/PROFSERV/{$arg[1]}"));
});


$chatMessageHandler->add(':deploy', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $dir = '/var/lib/bot';

    $roomgit = array(
        '#blongden.inviqa/$dhowlett.inviqa;66b8c356c0d9e779'   => 'git@github.com:PZCussons/drupal.git',
        '#grogers.inviqa/$gkemp.inviqa;18662fe1c4c1b330'       => 'git@github.com:PZCussons/drupal.git',
        '#blongden.inviqa/$jenkins-incubator;1d56633d322bb7fc' => 'git@github.com:PZCussons/drupal.git'
    );

    if (!array_key_exists($chatname['val'], $roomgit)) {
        $engine->invoke("CHATMESSAGE {$chatname['val']} I don't know about any deployments for this skype room.");
        return false;
    }
    $output = array();
    preg_match('/(?P<user>\w+)@(?P<host>[^:]+):(?P<path>.+)/', $roomgit[$chatname['val']], $url);
    $parent = explode('/', $url['path']);
    if (!file_exists("$dir/{$parent[0]}")) {
        mkdir("$dir/{$parent[0]}");
    }
    if (!file_exists("$dir/{$url['path']}")) {
        chdir($dir);
        exec("git clone {$roomgit[$chatname['val']]} {$url['path']} 2>&1", $output);
        chdir("$dir/{$url['path']}");
    } else {
        chdir("$dir/{$url['path']}");
        exec("git pull origin master 2>&1", $output);
    }
    chdir("$dir/{$url['path']}/tools/capistrano");

    $engine->invoke("CHATMESSAGE {$chatname['val']} I am deploying {$url['path']} to the development stage.");
    exec("cap deploy 2>&1", $output);
    $filename = uniqid();
    $log = "logs/$filename.txt";
    file_put_contents(__DIR__."/$log", implode("\n", $output));
    $engine->invoke("CHATMESSAGE {$chatname['val']} Deployment complete - check http://incubator.inviqa.com:9001/$log for details.");
});

$engine->add(':info', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $githubBase = "http://incubator.inviqa.com:9001/github.php";
    $jenkinsBase = "http://incubator.inviqa.com:9001/jenkins.php";
    $engine->invoke("CHATMESSAGE {$chatname['val']} For github integration, add this URL; $githubBase?id=".urlencode($chatname['val'])." as a commit hook in your github repository.\n\nFor Jenkins Notifications use $jenkinsBase?id=".urlencode($chatname['val'])."");
});

return $engine;
