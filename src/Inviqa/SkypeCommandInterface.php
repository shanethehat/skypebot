<?php

namespace Inviqa;

interface SkypeCommandInterface
{
    public function getCommand();
    
    public function getName();
    
    public function getArgument();
    
    public function getValue();
}
