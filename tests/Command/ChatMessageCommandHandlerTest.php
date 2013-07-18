<?php

use Inviqa\Command\ChatMessageCommandHandler;

class ChatMessageCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleReturnsFalseForInvalidCommand()
    {
        $handler = new ChatMessageCommandHandler();
        $this->assertFalse($handler->handleCommand('not chat message', 'name', 'arg', 'value'));
    }
    
    public function testHandleReturnsTrueForChatMessageCommand()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        
        $this->assertTrue($handler->handleCommand('CHATMESSAGE', 'name', 'arg', 'value'));
    }
}
