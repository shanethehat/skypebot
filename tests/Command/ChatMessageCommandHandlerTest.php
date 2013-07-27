<?php

use Inviqa\Command\ChatMessageCommandHandler;

class ChatMessageCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    private function getSkypeCommand($command)
    {
        $mock = $this->getMock('Inviqa\SkypeCommandInterface');
        $mock->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue($command));
        return $mock;
    }
    
    public function testHandleReturnsFalseForInvalidCommand()
    {
        $handler = new ChatMessageCommandHandler();
        $this->assertFalse($handler->handleCommand($this->getSkypeCommand('not chat message')));
    }
    
    public function testHandleReturnsTrueForChatMessageCommand()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        
        $this->assertTrue($handler->handleCommand($this->getSkypeCommand('CHATMESSAGE')));
    }
}
