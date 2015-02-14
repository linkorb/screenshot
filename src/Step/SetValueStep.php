<?php

namespace Screenshot\Step;

use Screenshot\Shooter;
use InvalidArgumentException;

class SetValueStep implements StepInterface
{
    private $id;
    private $value;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function execute(Shooter $shooter)
    {
        $id = $this->id;
        $shooter->addInfo("Setting value on " . $id);
        
        
        $session = $shooter->getSession();
        $page = $session->getPage();
        $element = $page->find(
            'css',
            '#' . $id
        );
        if (null === $element) {
            throw new InvalidArgumentException(sprintf('Could not find element by id: "%s"', $id));
        }
        $element->setValue($this->value);
        
    }
}
