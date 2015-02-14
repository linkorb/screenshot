<?php

namespace Screenshot\Step;

use Screenshot\Shooter;
use InvalidArgumentException;

class SelectStep implements StepInterface
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
        $shooter->addInfo("Selecting on " . $id);
        
        
        $session = $shooter->getSession();
        $page = $session->getPage();
        $element = $page->find(
            'css',
            '#' . $id
        );
        $element->selectOption($this->value);
        
        if (null === $element) {
            throw new InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
    }
}
