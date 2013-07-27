<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;

class ChatMessageCommandHandler implements CommandHandlerInterface
{
    const COMMAND = 'CHATMESSAGE';
    
    protected $engine;

    protected $commands = array();
    
    public function handleCommand($command, $name, $arg, $val)
    {
        if (self::COMMAND !== $command) {
            return false;
        }
        
        if ($arg == 'STATUS' && $val == 'RECEIVED') {
            $chatname = $this->getInfo($this->engine->invoke("GET CHATMESSAGE $name CHATNAME"));
            $handle = $this->getInfo($this->engine->invoke("GET CHATMESSAGE $name FROM_HANDLE"));
            $body = $this->getInfo($this->engine->invoke("GET CHATMESSAGE $name BODY"));
            printf(
                "%s %s: %s\n",
                $chatname['val'],
                $handle['val'],
                $body['val']
            );

            $msg = array_map('strtolower', explode(' ', $body['val']));
            if (array_key_exists($msg[0], $this->commands)) {
                $this->commands[$msg[0]]($this->engine, $chatname, $handle, $body);
            }

            //special case for Andrew "dog boy" Baker
            if ($handle['val'] == "abaker.inviqa" && $chatname['val'] == '#ben.longden/$rowan.merewood;1d3ab49e7f5995e1') {
                if (stristr(preg_replace('#[\W]#', '', $body['val']), 'dog') || stristr($body['val'], 'toivo') || stristr($body['val'], 'mansfield')) {
                    $this->engine->invoke("CHATMESSAGE {$chatname['val']} Potential dog story detected. :-O");
                    $this->engine->invoke("ALTER CHAT {$chatname['val']} KICK {$handle['val']}");
                }
            }
        }
        $this->engine->invoke("SET CHATMESSAGE $name SEEN");
        return true;
    }
    
    public function setEngine(SkypeEngine $engine)
    {
        $this->engine = $engine;
    }
    
    public function add($cmd, callable $action)
    {
        $this->commands[strtolower($cmd)] = $action;
        return $this;
    }
}
