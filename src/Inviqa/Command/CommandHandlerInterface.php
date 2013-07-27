<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;
use Inviqa\SkypeCommandInterface;

interface CommandHandlerInterface
{
    /**
     * Returns a boolean indicating whether the command has executed
     * 
     * @param string $command
     * @param string $name
     * @param string $arg
     * @param string $val
     * 
     * @return boolean
     */
    public function handleCommand(SkypeCommandInterface $command);
    
    public function setEngine(SkypeEngine $engine);
}
