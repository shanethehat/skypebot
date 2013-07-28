<?php

use Inviqa\Command\ChatMessage\DogBoyHandler;

class DogBoyHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $handler;
    private $roomName = '#ben.longden/$rowan.merewood;1d3ab49e7f5995e1';
    private $userName = 'abaker.inviqa';
    
    public function setup()
    {
        $this->handler = new DogBoyHandler();
    }
    
    public function testHandlerDoesNotWorkWithIncorrectHandle()
    {
        $chatnameCommand = $this->getChatnameCommand();
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $handleCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_the_dog_boy'));
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->never())
            ->method('getValue');
        
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesNotWorkWithIncorrectRoom()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_the_room'));
        
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->never())
            ->method('getValue');
        
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesNotWorkWithIncorrectMessage()
    {
        $chatnameCommand = $this->getChatnameCommand();
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('Nothing wrong here'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $this->handler->setEngine($engine);
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesFireForAToivo()
    {
        $chatnameCommand = $this->getChatnameCommand();
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('There is a (toivo) in this string'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->at(0))
            ->method('invoke')
            ->with("CHATMESSAGE {$this->roomName} Potential dog story detected. :-O");
        $engine->expects($this->at(1))
            ->method('invoke')
            ->with("ALTER CHAT {$this->roomName} KICK {$this->userName}");
        
        $this->handler->setEngine($engine);
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesFireForMansfield()
    {
        $chatnameCommand = $this->getChatnameCommand();
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('There is Mansfield in this string'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->at(0))
            ->method('invoke')
            ->with("CHATMESSAGE {$this->roomName} Potential dog story detected. :-O");
        $engine->expects($this->at(1))
            ->method('invoke')
            ->with("ALTER CHAT {$this->roomName} KICK {$this->userName}");
        
        $this->handler->setEngine($engine);
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesFireForADog()
    {
        $chatnameCommand = $this->getChatnameCommand();
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('There is a dog in this string'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->at(0))
            ->method('invoke')
            ->with("CHATMESSAGE {$this->roomName} Potential dog story detected. :-O");
        $engine->expects($this->at(1))
            ->method('invoke')
            ->with("ALTER CHAT {$this->roomName} KICK {$this->userName}");
        
        $this->handler->setEngine($engine);
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerDoesFireForADogContainingSpaces()
    {
        $chatnameCommand = $this->getChatnameCommand();
        $handleCommand = $this->getHandleCommand();
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('There is a d o g in this string'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->at(0))
            ->method('invoke')
            ->with("CHATMESSAGE {$this->roomName} Potential dog story detected. :-O");
        $engine->expects($this->at(1))
            ->method('invoke')
            ->with("ALTER CHAT {$this->roomName} KICK {$this->userName}");
        
        $this->handler->setEngine($engine);
        $this->handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    private function getChatnameCommand()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($this->roomName));
        return $chatnameCommand;
    }
     
    private function getHandleCommand()
    {
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $handleCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($this->userName));
        return $handleCommand;
    }
}
