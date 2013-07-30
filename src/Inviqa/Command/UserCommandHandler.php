<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;
use Inviqa\SkypeCommandInterface;

class UserCommandHandler implements CommandHandlerInterface
{
    const COMMAND = 'USER';
    
    protected $engine;
    
    public function handleCommand(SkypeCommandInterface $command)
    {
        if (self::COMMAND !== $command->getCommand()) {
            return false;
        }
        
        switch($command->getArgument()) {
            case 'BUDDYSTATUS':
                $this->handleBuddyStatus($command->getName(), $command->getValue());
                break;
            case 'RECIEVEDAUTHREQUEST':

                break;
        }
        
        return true;
    }
    
    public function setEngine(SkypeEngine $engine)
    {
        $this->engine = $engine;
    }
    
    protected function handleBuddyStatus($name, $val)
    {
        if ($val == 3) {
            // user accepted friend request - send welcome
            $this->engine->parse($this->engine->invoke("CHAT CREATE $name,$name"));
            $this->engine->parse($this->engine->invoke("CHATMEMBER $name SETROLETO MASTER"));
        }
    }
}
