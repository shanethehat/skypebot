<?php

use Inviqa\Command\ChatMessage\DeployHandler;

class DeployHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlerIsSkippedWithoutDeployKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_deploy'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $handler = new DeployHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerIsNotSkippedWithKeyword()
    {
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        
        $handler = new DeployHandler();
        $handler->setEngine($engine);
        
        $this->markTestIncomplete('Deploy handler needs more work before it can be properly tested');
    }
}
