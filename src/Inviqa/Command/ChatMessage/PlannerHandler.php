<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class PlannerHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    const PLANNER_URL = "https://docs.google.com/a/inviqa.com/spreadsheet/ccc?key=0AvKTHYI2dY1HdG1FakNCTE9IbWtVZDZGX2RDYkp5Q0E#gid=41";
    
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':planner', $body->getValue())) {
            return;
        }
        
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . self::PLANNER_URL);
    }
}
