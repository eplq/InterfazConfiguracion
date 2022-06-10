<?php

namespace ITEC\DAW\EPL\Configuracion\Tests;

use ITEC\DAW\EPL\Configuracion\INIConfig;
use PHPUnit\Framework\TestCase;

class INIConfigTest extends TestCase
{
    public function testCreateValue()
    {
        $ini = new INIConfig();
        $ini->openFile("config.ini");

        $this->assertTrue($ini->createValue("hola", "mundo"));
        $this->assertFalse($ini->createValue("hola", "mundo"));
        $this->assertFalse($ini->createValue("hola", new \DateTime()));

        $this->assertFalse($ini->createValue("array", [1, 2, 3]));
        $this->assertTrue($ini->createValue("numero", 123));
        $this->assertTrue($ini->createValue("booleano", false));

        return $ini;
    }

    /**
     * @depends testCreateValue
     */
    public function testReadValue(INIConfig $ini)
    {
        $this->assertFalse($ini->readValue("booleano"));
        $this->assertNull($ini->readValue("claveInexistente"));

        return $ini;
    }

    /**
     * @depends testReadValue
     */
    public function testRemoveKey(INIConfig $ini)
    {
        $ini->removeKey("hola");
        $this->assertNull($ini->readValue("hola"));

        return $ini;
    }

    /**
     * @depends testRemoveKey
     */
    public function testModifyValue(INIConfig $ini)
    {
        $ini->modifyValue("booleano", true);
        $this->assertTrue($ini->readValue("booleano"));

        $ini->modifyValue("booleano", new \DateTime());
        $this->assertTrue($ini->readValue("booleano"));

        $this->assertNull($ini->modifyValue("clave inexistente", "cualquier cosa"));

        return $ini;
    }

    /**
     * @depends testModifyValue
     */
    public function testCloseFile(INIConfig $ini)
    {
        $ini->closeFile();
        $this->assertFileEquals("tests/final.ini", "config.ini");
    }

    public function testOpenAgain()
    {
        $ini = new INIConfig();
        $ini->openFile("config.ini");

        $this->assertEquals(123, $ini->readValue("numero"));

        $ini->closeFile();
        unlink("config.ini");
    }
}
