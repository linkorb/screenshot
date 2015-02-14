<?php

namespace Screenshot\Loader;

use Screenshot\Script;

class JsonScriptLoader
{
    public function load($filename, $shooter)
    {
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        //print_r($data);
        $script = new Script();
        $script->setName($data['name']);
        foreach ($data['steps'] as $stepdata) {
            
            $type = $stepdata['type'];
            $p = $stepdata['parameters'];

            $className = "Screenshot\\Step\\" . $type . "Step";
            $step = new $className($shooter);
            foreach ($stepdata['parameters'] as $key => $value) {
                $setter = 'set' . ucfirst($key);
                $step->$setter($value);
            }
            $script->addStep($step);
        }
        //print_r($script); exit();
        return $script;
        
    }
}
