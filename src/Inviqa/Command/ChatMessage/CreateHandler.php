<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class CreateHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':create', $body->getValue())) {
            return;
        }
        
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} Sure. Why not.");
    }
}
