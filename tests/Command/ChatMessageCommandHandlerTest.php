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
        $mockCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $mockCommand->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('not chat message'));
        
        $handler = new ChatMessageCommandHandler();
        $this->assertFalse($handler->handleCommand($mockCommand));
    }
    
    public function testHandleReturnsTrueForChatMessageCommand()
    {
        $mockCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $mockCommand->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('CHATMESSAGE'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        
        $this->assertTrue($handler->handleCommand($mockCommand));
    }
    
    public function testWrongArgumentDoesNotRunHandlers()
    {
        $mockCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $mockCommand->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('CHATMESSAGE'));
        $mockCommand->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('NAME'));
        $mockCommand->expects($this->once())
            ->method('getArgument')
            ->will($this->returnValue('not status'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('SET CHATMESSAGE NAME SEEN');
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        
        $handler->handleCommand($mockCommand);
    }
    
    public function testWrongValueDoesNotRunHandlers()
    {
        $mockCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $mockCommand->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('CHATMESSAGE'));
        $mockCommand->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('NAME'));
        $mockCommand->expects($this->once())
            ->method('getArgument')
            ->will($this->returnValue('STATUS'));
        $mockCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not recieved'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('SET CHATMESSAGE NAME SEEN');
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        
        $handler->handleCommand($mockCommand);
    }
    
    public function testCorrectArgumentAndValueRunsHandlers()
    {
        $mockCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $mockCommand->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('CHATMESSAGE'));
        $mockCommand->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('NAME'));
        $mockCommand->expects($this->once())
            ->method('getArgument')
            ->will($this->returnValue('STATUS'));
        $mockCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('RECEIVED'));
        
        $mockHandler = $this->getMock('Inviqa\Command\ChatMessage\ChatMessageHandlerInterface');
        $mockHandler->expects($this->once())
            ->method('handle')
            ->with(
                $this->isInstanceOf('Inviqa\SkypeCommandInterface'),
                $this->isInstanceOf('Inviqa\SkypeCommandInterface'),
                $this->isInstanceOf('Inviqa\SkypeCommandInterface')
            );
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->at(0))
            ->method('invoke')
            ->with('GET CHATMESSAGE NAME CHATNAME');
        $engine->expects($this->at(1))
            ->method('invoke')
            ->with('GET CHATMESSAGE NAME FROM_HANDLE');
        $engine->expects($this->at(2))
            ->method('invoke')
            ->with('GET CHATMESSAGE NAME BODY');
        $engine->expects($this->at(3))
            ->method('invoke')
            ->with('SET CHATMESSAGE NAME SEEN');
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        $handler->add($mockHandler);
        
        $handler->handleCommand($mockCommand);
    }
    
    public function testEngineIsSetWhenHandlerIsAdded()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockHandler = $this->getMock('Inviqa\Command\ChatMessage\ChatMessageHandlerInterface');
        $mockHandler->expects($this->once())
            ->method('setEngine')
            ->with($engine);
        
        $handler = new ChatMessageCommandHandler();
        $handler->setEngine($engine);
        $handler->add($mockHandler);
    }
}
