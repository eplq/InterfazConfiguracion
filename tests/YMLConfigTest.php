<?php

namespace ITEC\DAW\EPL\Configuracion\Tests;

use ITEC\DAW\EPL\Configuracion\YMLConfig;
use PHPUnit\Framework\TestCase;

class YMLConfigTest extends TestCase
{
    public function testCreateValue()
    {
        $yml = new YMLConfig();
        $yml->openFile("config.yml");

        $this->assertTrue($yml->createValue("hola", "mundo"));
        $this->assertFalse($yml->createValue("hola", "mundo"));
        $this->assertFalse($yml->createValue("hola", new \DateTime()));

        $this->assertTrue($yml->createValue("array", [1, 2, 3]));
        $this->assertTrue($yml->createValue("numero", 123));
        $this->assertTrue($yml->createValue("booleano", false));

        return $yml;
    }

    /**
     * @depends testCreateValue
     */
    public function testReadValue(YMLConfig $yml)
    {
        $this->assertFalse($yml->readValue("booleano"));
        $this->assertNull($yml->readValue("claveInexistente"));

        return $yml;
    }

    /**
     * @depends testReadValue
     */
    public function testRemoveKey(YMLConfig $yml)
    {
        $yml->removeKey("hola");
        $this->assertNull($yml->readValue("hola"));

        return $yml;
    }

    /**
     * @depends testRemoveKey
     */
    public function testModifyValue(YMLConfig $yml)
    {
        $yml->modifyValue("booleano", true);
        $this->assertTrue($yml->readValue("booleano"));

        $yml->modifyValue("booleano", new \DateTime());
        $this->assertTrue($yml->readValue("booleano"));

        $this->assertNull($yml->modifyValue("clave inexistente", "cualquier cosa"));

        return $yml;
    }

    /**
     * @depends testModifyValue
     */
    public function testCloseFile(YMLConfig $yml)
    {
        $yml->closeFile();
        $this->assertFileEquals("tests/final.yml", "config.yml");
    }

    public function testOpenAgain()
    {
        $yml = new YMLConfig();
        $yml->openFile("config.yml");

        $this->assertEquals(123, $yml->readValue("numero"));

        $yml->closeFile();
        unlink("config.yml");
    }
}
