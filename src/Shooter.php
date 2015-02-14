<?php

namespace Screenshot;

use Behat\Mink\Session;

class Shooter
{
    private $session;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function runScript(Script $script)
    {
        $this->addInfo("Starting browser");
        $this->session->start();
        
        $chromeheight = 144/2;
        $this->addInfo("Resizing browser");
        $this->session->resizeWindow(1024, 768 + $chromeheight, null);

        foreach ($script->getSteps() as $step) {
            $step->execute($this);
        }
        
        $this->addInfo("Closing browser");
        $this->session->stop();
        $this->addInfo("Done");
        
    }
    
    public function addInfo($message, $details = array()) {
        echo " - $message\n";
    }
}
