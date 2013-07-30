<?php

use Inviqa\Command\ChatMessage\PlannerHandler;

class PlannerHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlerIsSkippedWithoutPlannerKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_planner'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $handler = new PlannerHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerSendsResponseForKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('CHATNAME'));
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(':planner'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('CHATMESSAGE CHATNAME ' . PlannerHandler::PLANNER_URL);
        
        $handler = new PlannerHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
