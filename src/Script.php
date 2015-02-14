<?php

namespace Screenshot;

class Script
{
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    private $steps = array();
    
    public function addStep($step)
    {
        $this->steps[] = $step;
    }
    
    public function getSteps()
    {
        return $this->steps;
    }
}
