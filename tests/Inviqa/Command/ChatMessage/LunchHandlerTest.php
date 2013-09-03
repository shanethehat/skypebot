<?php

use Inviqa\Command\ChatMessage\LunchHandler;

class LunchHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function getLunchService()
    {
        return $this->getMock('\Inviqa\Lunch\LunchServiceInterface');
    }

    public function testLunchHandlerAcceptsLunchService()
    {
        $handler = new LunchHandler($this->getLunchService());
        $this->assertInstanceOf('Inviqa\Command\ChatMessage\LunchHandler', $handler);
    }

    public function testHandlerIsSkippedWithoutLunchKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');

        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');

        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_lunch'));

        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');

        $handler = new LunchHandler($this->getLunchService());
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
