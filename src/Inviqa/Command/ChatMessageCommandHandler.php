<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;
use Inviqa\SkypeCommandInterface;

class ChatMessageCommandHandler implements CommandHandlerInterface
{
    const COMMAND = 'CHATMESSAGE';
    
    protected $engine;

    protected $commands = array();
    
    public function handleCommand(SkypeCommandInterface $command)
    {
        if (self::COMMAND !== $command->getCommand()) {
            return false;
        }
        
        $name = $command->getName();
        
        if ($command->getArgument() == 'STATUS' && $command->getValue() == 'RECEIVED') {
            $chatname = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name CHATNAME"));
            $handle = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name FROM_HANDLE"));
            $body = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name BODY"));
            printf(
                "%s %s: %s\n",
                $chatname->getValue(),
                $handle->getValue(),
                $body->getValue()
            );

            $msg = array_map('strtolower', explode(' ', $body->getValue()));
            if (array_key_exists($msg[0], $this->commands)) {
                $this->commands[$msg[0]]($this->engine, $chatname, $handle, $body);
            }

            //special case for Andrew "dog boy" Baker
            if ($handle->getValue() == "abaker.inviqa" && $chatname->getValue() == '#ben.longden/$rowan.merewood;1d3ab49e7f5995e1') {
                if (stristr(preg_replace('#[\W]#', '', $body->getValue()), 'dog') || stristr($body->getValue(), 'toivo') || stristr($body->getValue(), 'mansfield')) {
                    $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} Potential dog story detected. :-O");
                    $this->engine->invoke("ALTER CHAT {$chatname->getValue()} KICK {$handle->getValue()}");
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
