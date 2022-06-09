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

        if (!file_exists($this->filename)) {
            touch($this->filename);
        }

        $this->readFile();
    }

    private function readFile()
    {
        $fileData = file_get_contents($this->filename);
        $this->jsonData = json_decode($fileData, true) ?? [];
    }

    public function closeFile(): void
    {
        $this->saveFile();
    }

    private function saveFile(): void
    {
        file_put_contents($this->filename, json_encode($this->jsonData));
    }

    public function readValue(string $key): string | float | int | bool | array | null
    {
        if (array_key_exists($key, $this->jsonData)) {
            return $this->jsonData[$key];
        }

        return null;
    }

    public function createValue(string $key, $value): bool
    {
        if (!is_numeric($value) && !is_bool($value) && !is_array($value) && !is_string($value)) {
            return false;
        }

        if (array_key_exists($key, $this->jsonData)) {
            return false;
        }

        $this->jsonData[$key] = $value;
        return true;
    }

    public function removeKey(string $key): void
    {
        if (array_key_exists($key, $this->jsonData)) {
            unset($this->jsonData[$key]);
        }
    }

    public function modifyValue(string $key, $newValue): void
    {
        if (!is_numeric($newValue) && !is_bool($newValue) && !is_array($newValue) && !is_string($newValue)) {
            return;
        }

        if (array_key_exists($key, $this->jsonData)) {
            $this->jsonData[$key] = $newValue;
        }
    }
}
