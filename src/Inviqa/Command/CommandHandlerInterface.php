<?php

namespace Inviqa\Command;

use Inviqa\SkypeEngine;

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
    public function handleCommand($command, $name, $arg, $val);
    
    public function setEngine(SkypeEngine $engine);
}
