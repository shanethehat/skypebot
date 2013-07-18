<?php

use Inviqa\SkypeEngine;

class SkypeEngineTest extends PHPUnit_Framework_TestCase
{
    protected $dbus;
    protected $engine;
    
    private function expectSkype($i, $req, $res='')
    {
        $this->dbus->expects($this->at($i))->method('Invoke')->with($req)->will($this->returnValue($res));
    }
    
    public function setup()
    {
        $this->dbus = $this->getMockBuilder('DbusObject')
            ->setMethods(array('Invoke'))
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->engine = new SkypeEngine($this->dbus);
    }
    
    public function testInvokeCallsDbus()
    {
        $this->expectSkype(0, $this->equalTo('test message'));
        $this->engine->invoke('test message');
    }
    
    public function testParseCallsAddedCommand()
    {
        $command = $this->getMock('Inviqa\Command\CommandHandlerInterface');
        $command->expects($this->once())
            ->method('handleCommand')
            ->with('cmd', 'name', 'arg', 'val')
            ->will($this->returnValue(true));
        
        $this->engine->addCommandhandler($command);
        
        $this->engine->parse('cmd name arg val');
    }
    
    public function testExecutedCommandBreaksChain()
    {
        $command1 = $this->getMock('Inviqa\Command\CommandHandlerInterface');
        $command1->expects($this->once())
            ->method('handleCommand')
            ->with('cmd', 'name', 'arg', 'val')
            ->will($this->returnValue(true));
        
        $command2 = $this->getMock('Inviqa\Command\CommandHandlerInterface');
        $command2->expects($this->never())
            ->method('handleCommand');
        
        $this->engine->addCommandhandler($command1);
        $this->engine->addCommandhandler($command2);
        
        $this->engine->parse('cmd name arg val');
    }
    
    public function testNonExecutedCommandDoesNotBreakChain()
    {
        $command1 = $this->getMock('Inviqa\Command\CommandHandlerInterface');
        $command1->expects($this->once())
            ->method('handleCommand')
            ->with('cmd', 'name', 'arg', 'val')
            ->will($this->returnValue(false));
        
        $command2 = $this->getMock('Inviqa\Command\CommandHandlerInterface');
        $command2->expects($this->once())
            ->method('handleCommand')
            ->with('cmd', 'name', 'arg', 'val')
            ->will($this->returnValue(true));
        
        $this->engine->addCommandhandler($command1);
        $this->engine->addCommandhandler($command2);
        
        $this->engine->parse('cmd name arg val');
    }
}
