<?php

use Inviqa\Command\UserCommandHandler;

class UserCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleReturnsFalseForInvalidCommand()
    {
        $handler = new UserCommandHandler();
        $this->assertFalse($handler->handleCommand('not user', 'name', 'arg', 'value'));
    }
    
    public function testHandleReturnsTrueForUserCommand()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new UserCommandHandler();
        $handler->setEngine($engine);
        
        $this->assertTrue($handler->handleCommand('USER', 'name', 'arg', 'value'));
    }
}
