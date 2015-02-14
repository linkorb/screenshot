<?php

namespace Screenshot\Step;

use Screenshot\Shooter;
use InvalidArgumentException;

class ClickStep implements StepInterface
{
    private $id;
    private $name;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function execute(Shooter $shooter)
    {
        $id = $this->id;
        $shooter->addInfo("Clicking " . $id);
        
        
        $session = $shooter->getSession();
        $page = $session->getPage();
        
        if ($id) {
            $element = $page->find(
                'css',
                '#' . $id
            );
        } else {
            $element = $page->find(
                'css',
                'input[name="' . $this->name . '"]'
            );
        }
        if (null === $element) {
            throw new InvalidArgumentException(sprintf('Could find element with id: "%s"', $id));
        }
        $element->press();
        
    }
}
