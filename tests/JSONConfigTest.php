<?php

namespace ITEC\DAW\EPL\Configuracion\Tests;

use ITEC\DAW\EPL\Configuracion\JSONConfig;
use PHPUnit\Framework\TestCase;

class JSONConfigTest extends TestCase
{
    public function testOpenFile()
    {
        $json = new JSONConfig();
        $json->openFile("config.json");
        $json->closeFile();
    }
}
