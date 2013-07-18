<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;

class UserCommandHandler implements CommandHandlerInterface
{
    const COMMAND = 'USER';
    
    protected $engine;
    
    public function handleCommand($command, $name, $arg, $val)
    {
        if (self::COMMAND !== $command) {
            return false;
        }
        
        switch($arg) {
            case 'BUDDYSTATUS':
                $this->handleBuddyStatus($name, $val);
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
