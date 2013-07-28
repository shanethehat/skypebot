<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class PingHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':ping', $body->getValue())) {
            return;
        }
        
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} Pong!");
    }
}
