<?php

namespace ITEC\DAW\EPL\Configuracion\Tests;

use ITEC\DAW\EPL\Configuracion\JSONConfig;
use PHPUnit\Framework\TestCase;

class JSONConfigTest extends TestCase
{
    public function testCreateValue()
    {
        $json = new JSONConfig();
        $json->openFile("config.json");

        $this->assertTrue($json->createValue("hola", "mundo"));
        $this->assertFalse($json->createValue("hola", "mundo"));
        $this->assertFalse($json->createValue("hola", new \DateTime()));

        $this->assertTrue($json->createValue("array", [1, 2, 3]));
        $this->assertTrue($json->createValue("numero", 123));
        $this->assertTrue($json->createValue("booleano", false));

        return $json;
    }

    /**
     * @depends testCreateValue
     */
    public function testReadValue(JSONConfig $json)
    {
        $this->assertFalse($json->readValue("booleano"));
        $this->assertNull($json->readValue("claveInexistente"));

        return $json;
    }

    /**
     * @depends testReadValue
     */
    public function testRemoveKey(JSONConfig $json)
    {
        $json->removeKey("hola");
        $this->assertNull($json->readValue("hola"));

        return $json;
    }

    /**
     * @depends testRemoveKey
     */
    public function testModifyValue(JSONConfig $json)
    {
        $json->modifyValue("booleano", true);
        $this->assertTrue($json->readValue("booleano"));

        $json->modifyValue("booleano", new \DateTime());
        $this->assertTrue($json->readValue("booleano"));

        $this->assertNull($json->modifyValue("clave inexistente", "cualquier cosa"));

        return $json;
    }

    /**
     * @depends testModifyValue
     */
    public function testCloseFile(JSONConfig $json)
    {
        $json->closeFile();
        $this->assertJsonFileEqualsJsonFile("tests/final.json", "config.json");
    }

    public function testOpenAgain()
    {
        $json = new JSONConfig();
        $json->openFile("config.json");

        $this->assertEquals(123, $json->readValue("numero"));

        $json->closeFile();
        unlink("config.json");
    }
}
