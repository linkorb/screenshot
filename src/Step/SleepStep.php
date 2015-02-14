<?php

namespace Screenshot\Step;

use Screenshot\Shooter;

class SleepStep implements StepInterface
{
    private $ms = 0;
    
    public function setMs($ms)
    {
        $this->ms = $ms;
    }
    
    public function execute(Shooter $shooter)
    {
        $ms = $this->ms;
        $shooter->addInfo("Sleeping " . $ms . "ms");
        $session = $shooter->getSession();
        $session->wait($ms);
    }
}
