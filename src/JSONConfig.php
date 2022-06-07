<?php

namespace ITEC\DAW\EPL\Configuracion;

class JSONConfig implements IConfiguracion
{
    private string $filename;
    private $file;
    private array $jsonData;

    public function openFile(string $filename): void
    {
        $this->filename = $filename;
        $this->file = fopen($filename, "w+");

        $this->readFile();
    }

    private function readFile()
    {
        $filesize = filesize($this->filename);
        $fileData = "[]";
        if ($filesize > 0) {
            $fileData = fread($this->file, filesize($this->filename));
        }

        $this->jsonData = json_decode($fileData, true);
    }

    public function closeFile(): void
    {
        $this->saveFile();
        fclose($this->file);
    }

    private function saveFile(): void
    {
        fwrite($this->file, json_encode($this->jsonData));
    }

    public function readValue(string $key): string | float | int | bool | array | null
    {
        if (array_key_exists($key, $this->jsonData)) {
            return $this->jsonData[$key];
        }

        return null;
    }

    public function createValue(string $key, $value): void
    {
        if (!is_numeric($value) || !is_bool($value) || !is_array($value)) {
            return;
        }

        if (array_key_exists($key, $this->jsonData)) {
            return;
        }

        $this->jsonData[$key] = $value;
    }

    public function removeKey(string $key): void
    {
        if (array_key_exists($key, $this->jsonData)) {
            unset($this->jsonData[$key]);
        }
    }

    public function modifyValue(string $key, $newValue): void
    {
        if (!is_numeric($newValue) || !is_bool($newValue) || !is_array($newValue)) {
            return;
        }

        if (array_key_exists($key, $this->jsonData)) {
            $this->jsonData[$key] = $newValue;
        }
    }
}
