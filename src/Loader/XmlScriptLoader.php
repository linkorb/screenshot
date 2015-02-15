<?php

namespace Screenshot\Loader;

use Screenshot\Script;

class XmlScriptLoader
{
    public function load($filename, $shooter)
    {
        $xml = simplexml_load_file($filename);
        $script = new Script();
        $script->setName((string)$xml['name']);
        foreach ($xml->Steps->children() as $stepnode) {
            $type = $stepnode->getName();

            $className = "Screenshot\\Step\\" . $type . "Step";
            $step = new $className($shooter);
            
            foreach ($stepnode->attributes() as $key => $value) {
                $setter = 'set' . ucfirst((string)$key);
                $step->$setter((string)$value);
            }
            $script->addStep($step);
            
        }
        return $script;
    }
}
