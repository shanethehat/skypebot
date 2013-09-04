<?php

use Inviqa\Command\ChatMessage\LunchHandler;

class LunchHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function getLunchService()
    {
        return $this->getMock('\Inviqa\Lunch\LunchServiceInterface');
    }

    protected function getEngineMock()
    {
        return $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
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

        $engine = $this->getEngineMock();
        $engine->expects($this->never())
            ->method('invoke');

        $handler = new LunchHandler($this->getLunchService());
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }

    public function testHandlerReturnsErrorForBadChatName()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->exactly(2))
            ->method('getValue')
            ->will($this->returnValue('this-is-not-the-chat-you-are-looking-for'));

        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');

        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':lunch'));

        $engine = $this->getEngineMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('CHATMESSAGE this-is-not-the-chat-you-are-looking-for This is not a lunch channel.');

        $handler = new LunchHandler($this->getLunchService());
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }

    public function testHandlerReturnsFullLunchMessage()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->exactly(2))
            ->method('getValue')
            ->will($this->returnValue(LunchHandler::SHEFFIELD_LUNCH_CHANNEL));

        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');

        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':lunch'));

        $engine = $this->getEngineMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->will($this->returnValue("CHATMESSAGE " . LunchHandler::SHEFFIELD_LUNCH_CHANNEL . "\nShopper: Han Solo\nWasher: Chewie"));

        $shopper = $this->getMock('Inviqa\Lunch\MemberInterface');
        $shopper->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Han Solo'));

        $washer = $this->getMock('Inviqa\Lunch\MemberInterface');
        $washer->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Chewie'));

        $lunchService = $this->getLunchService();
        $lunchService->expects($this->once())
            ->method('getCurrentShopper')
            ->will($this->returnValue($shopper));
        $lunchService->expects($this->once())
            ->method('getCurrentWasher')
            ->will($this->returnValue($washer));

        $handler = new LunchHandler($lunchService);
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }

    public function testHandlerGetsNextShopper()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->exactly(2))
            ->method('getValue')
            ->will($this->returnValue(LunchHandler::SHEFFIELD_LUNCH_CHANNEL));

        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');

        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':lunch next shopper'));

        $engine = $this->getEngineMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->will($this->returnValue("CHATMESSAGE " . LunchHandler::SHEFFIELD_LUNCH_CHANNEL . "\nShopper: Yoda\nWasher: Chewie"));

        $shopper = $this->getMock('Inviqa\Lunch\MemberInterface');
        $shopper->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Yoda'));

        $washer = $this->getMock('Inviqa\Lunch\MemberInterface');
        $washer->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Chewie'));

        $lunchService = $this->getLunchService();
        $lunchService->expects($this->once())
            ->method('getNextShopper')
            ->will($this->returnValue($shopper));
        $lunchService->expects($this->never())
            ->method('getCurrentShopper');
        $lunchService->expects($this->once())
            ->method('getCurrentWasher')
            ->will($this->returnValue($washer));

        $handler = new LunchHandler($lunchService);
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }

    public function testHandlerGetsNextWasher()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->exactly(2))
            ->method('getValue')
            ->will($this->returnValue(LunchHandler::SHEFFIELD_LUNCH_CHANNEL));

        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');

        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':lunch next washer'));

        $engine = $this->getEngineMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->will($this->returnValue("CHATMESSAGE " . LunchHandler::SHEFFIELD_LUNCH_CHANNEL . "\nShopper: Yoda\nWasher: Chewie"));

        $shopper = $this->getMock('Inviqa\Lunch\MemberInterface');
        $shopper->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Han Solo'));

        $washer = $this->getMock('Inviqa\Lunch\MemberInterface');
        $washer->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Yoda'));

        $lunchService = $this->getLunchService();
        $lunchService->expects($this->once())
            ->method('getNextWasher')
            ->will($this->returnValue($washer));
        $lunchService->expects($this->once())
            ->method('getCurrentShopper')
            ->will($this->returnValue($shopper));
        $lunchService->expects($this->never())
            ->method('getCurrentWasher');

        $handler = new LunchHandler($lunchService);
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
