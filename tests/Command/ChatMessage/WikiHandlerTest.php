<?php

use Inviqa\Command\ChatMessage\WikiHandler;

class WikiHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlerIsSkippedWithoutWikiKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_wiki'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $handler = new WikiHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerWarnsWhenNoArguementIsProvided()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('CHATNAME'));
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':wiki'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('CHATMESSAGE CHATNAME You must supply a wiki name, e.g. :wiki Incubator');
        
        $handler = new WikiHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerSendsResponseForKeywordWithArgument()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('CHATNAME'));
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':wiki Incubator'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('CHATMESSAGE CHATNAME ' . WikiHandler::WIKI_URL . '/Incubator');
        
        $handler = new WikiHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
