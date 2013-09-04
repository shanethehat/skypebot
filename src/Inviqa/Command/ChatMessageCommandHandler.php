<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;
use Inviqa\SkypeCommand;
use Inviqa\SkypeCommandInterface;

class ChatMessageCommandHandler implements CommandHandlerInterface
{
    const COMMAND = 'CHATMESSAGE';

    protected $engine;

    protected $handlers = array();

    public function handleCommand(SkypeCommandInterface $command)
    {
        if (self::COMMAND !== $command->getCommand()) {
            return false;
        }

        $name = $command->getName();

        if ($command->getArgument() === 'STATUS' && $command->getValue() === 'RECEIVED') {
            $chatname = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name CHATNAME"));
            $handle = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name FROM_HANDLE"));
            $body = new SkypeCommand($this->engine->invoke("GET CHATMESSAGE $name BODY"));

            foreach ($this->handlers as $messageHandler) {
                $messageHandler->handle($chatname, $handle, $body);
            }
        }
        $this->engine->invoke("SET CHATMESSAGE $name SEEN");
        return true;
    }

    public function setEngine(SkypeEngine $engine)
    {
        $this->engine = $engine;
    }

    public function add(ChatMessage\ChatMessageHandlerInterface $messageHandler)
    {
        $messageHandler->setEngine($this->engine);
        $this->handlers[] = $messageHandler;
        return $this;
    }
}
