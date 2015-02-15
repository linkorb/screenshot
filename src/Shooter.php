<?php

namespace Screenshot;

use Behat\Mink\Session;
use ObjectStorage\Service as ObjectStorageService;

class Shooter
{
    private $session;
    private $storageservice;
    
    public function __construct(Session $session, ObjectStorageService $storageservice)
    {
        $this->session = $session;
        $this->storageservice = $storageservice;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getStorageService()
    {
        return $this->storageservice;
    }
    
    private $width = 1024;
    
    public function getWidth()
    {
        return $this->width;
    }
    
    private $height = 768;
    
    public function getHeight()
    {
        return $this->height;
    }
    
    public function runScript(Script $script)
    {
        $this->addInfo("Starting browser");
        $this->session->start();
        
        $chromeheight = 144/2;
        $this->addInfo("Resizing browser " . $this->getWidth() . "x" . $this->getHeight());
        $this->session->resizeWindow($this->getWidth(), $this->getHeight() + $chromeheight, null);

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
