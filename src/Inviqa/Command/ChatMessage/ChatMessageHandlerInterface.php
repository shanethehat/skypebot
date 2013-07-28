<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;

interface ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body);
    public function setEngine(SkypeEngine $engine);
}
