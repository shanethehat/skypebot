<?php

namespace Inviqa;

class SkypeCommand implements SkypeCommandInterface
{
    protected $command;
    protected $name;
    protected $argument;
    protected $value;
    
    public function __construct($commandString)
    {
        $this->setValues($commandString);
    }
    
    public function getCommand()
    {
        return $this->command;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getArgument()
    {
        return $this->argument;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    protected function setValues($commandString)
    {
        if (strlen($commandString) === 0) {
            return;
        }
        $split = explode(' ', $commandString) + array(null, null, null);
        list($this->command, $this->name, $this->argument) = $split;
        if (count($split) > 3) {
            $this->value = implode(' ', array_slice($split, 3));
        }
    }
}
