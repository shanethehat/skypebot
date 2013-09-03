<?php

use Inviqa\Command\ChatMessage\MagentoHandler;

class MagentoHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlerIsSkippedWithoutMagentoKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_magento'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $handler = new MagentoHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerSendsResponseForKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('CHATNAME'));
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(':magento'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with($this->stringStartsWith('CHATMESSAGE CHATNAME '));
        
        $handler = new MagentoHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
