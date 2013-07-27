<?php

use Inviqa\SkypeCommand;

class SkypeCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandPopulatesWithFourPartString()
    {
        $command = new SkypeCommand('command name argument value');
        $this->assertEquals('command', $command->getCommand());
        $this->assertEquals('name', $command->getName());
        $this->assertEquals('argument', $command->getArgument());
        $this->assertEquals('value', $command->getValue());
    }
    
    public function testCommandPopulatesWithFivePartString()
    {
        $command = new SkypeCommand('command name argument value1 value2');
        $this->assertEquals('command', $command->getCommand());
        $this->assertEquals('name', $command->getName());
        $this->assertEquals('argument', $command->getArgument());
        $this->assertEquals('value1 value2', $command->getValue());
    }
    
    public function testCommandPopulatesWithAllNullValuesOnEmptyCommandString()
    {
        $command = new SkypeCommand('');
        $this->assertNull($command->getCommand());
        $this->assertNull($command->getName());
        $this->assertNull($command->getArgument());
        $this->assertNull($command->getValue());
    }
    
    public function testCommandPopulatesWithNullValuesIfNoSpaces()
    {
        $command = new SkypeCommand('commandNameArgumentValue');
        $this->assertEquals('commandNameArgumentValue', $command->getCommand());
        $this->assertNull($command->getName());
        $this->assertNull($command->getArgument());
        $this->assertNull($command->getValue());
    }
    
    public function testOnlyValuePopulatesWithNull()
    {
        $command = new SkypeCommand('command name argument');
        $this->assertEquals('command', $command->getCommand());
        $this->assertEquals('name', $command->getName());
        $this->assertEquals('argument', $command->getArgument());
        $this->assertNull($command->getValue());
    }
}
