<?php

use Inviqa\Command\UserCommandHandler;

class UserCommandHandlerTest extends \PHPUnit_Framework_TestCase
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
        $handler = new UserCommandHandler();
        $this->assertFalse($handler->handleCommand($this->getSkypeCommand('not user')));
    }
    
    public function testHandleReturnsTrueForUserCommand()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new UserCommandHandler();
        $handler->setEngine($engine);
        
        $this->assertTrue($handler->handleCommand($this->getSkypeCommand('USER')));
    }
}
