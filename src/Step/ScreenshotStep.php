<?php

namespace Screenshot\Step;

use Screenshot\Shooter;

class ScreenshotStep implements StepInterface
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
    
    public function execute(Shooter $shooter)
    {
        $shooter->addInfo("Taking screenshot: " . $this->name);
        
        $session = $shooter->getSession();
        $screenshot = $session->getDriver()->getScreenshot();
        file_put_contents($this->name .'.png', $screenshot);
                
    }
}
