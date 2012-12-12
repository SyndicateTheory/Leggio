<?php

namespace Leggio\Parser;

use Symfony\Component\Yaml\Parser as YamlParser;

class Parser
{
    public function parse($filename)
    {
        list($null, $meta, $body) = explode('---', file_get_contents($filename), 3);
        
        $parser = new YamlParser();
        $obj = $parser->parse($meta);
        $obj['body'] = trim($body);
        
        return $obj;
    }
}