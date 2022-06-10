<?php

namespace ITEC\DAW\EPL\Configuracion\Tests;

use ITEC\DAW\EPL\Configuracion\CSVConfig;
use PHPUnit\Framework\TestCase;

class CSVConfigTest extends TestCase
{
    public function testCreateValue()
    {
        $csv = new CSVConfig();
        $csv->openFile("config.csv");

        $this->assertTrue($csv->createValue("hola", "triste realidad"));
        $this->assertFalse($csv->createValue("hola", "mundo"));
        $this->assertFalse($csv->createValue("hola", new \DateTime()));

        $this->assertFalse($csv->createValue("array", [1, 2, 3]));
        $this->assertTrue($csv->createValue("numero", 123));
        $this->assertTrue($csv->createValue("booleano", false));

        return $csv;
    }

    /**
     * @depends testCreateValue
     */
    public function testReadValue(CSVConfig $csv)
    {
        $this->assertFalse($csv->readValue("booleano"));
        $this->assertNull($csv->readValue("claveInexistente"));

        return $csv;
    }

    /**
     * @depends testReadValue
     */
    public function testRemoveKey(CSVConfig $csv)
    {
        $csv->removeKey("numero");
        $this->assertNull($csv->readValue("numero"));

        return $csv;
    }

    /**
     * @depends testRemoveKey
     */
    public function testModifyValue(CSVConfig $csv)
    {
        $csv->modifyValue("booleano", true);
        $this->assertTrue($csv->readValue("booleano"));

        $csv->modifyValue("booleano", new \DateTime());
        $this->assertTrue($csv->readValue("booleano"));

        $this->assertNull($csv->modifyValue("clave inexistente", "cualquier cosa"));

        return $csv;
    }

    /**
     * @depends testModifyValue
     */
    public function testCloseFile(CSVConfig $csv)
    {
        $csv->closeFile();
        $this->assertFileEquals("tests/final.csv", "config.csv");
    }

    public function testOpenAgain()
    {
        $csv = new CSVConfig();
        $csv->openFile("config.csv");

        $this->assertEquals("triste realidad", $csv->readValue("hola"));

        $csv->closeFile();
        unlink("config.csv");
    }
}
