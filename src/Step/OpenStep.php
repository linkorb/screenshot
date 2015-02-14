<?php

namespace Screenshot\Step;

use Screenshot\Shooter;

class OpenStep implements StepInterface
{
    private $url;
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function execute(Shooter $shooter)
    {
        $url = $this->url;
        $shooter->addInfo("Opening " . $url);
        $session = $shooter->getSession();
        $session->visit($url);
    }
}
