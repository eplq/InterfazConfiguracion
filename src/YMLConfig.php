<?php

namespace ITEC\DAW\EPL\Configuracion;

use Symfony\Component\Yaml\Yaml;

class YMLConfig extends File implements IConfiguracion
{
    private array $ymlData;

    protected function readFile(): void
    {
        $this->ymlData = Yaml::parse($this->fileData) ?? [];
    }

    protected function saveFile(): void
    {
        $this->fileData = Yaml::dump($this->ymlData);
    }

    public function readValue(string $key): string | float | int | bool | array | null
    {
        if (array_key_exists($key, $this->ymlData)) {
            return $this->ymlData[$key];
        }

        return null;
    }

    public function createValue(string $key, $value): bool
    {
        if (!is_numeric($value) && !is_bool($value) && !is_array($value) && !is_string($value)) {
            return false;
        }

        if (array_key_exists($key, $this->ymlData)) {
            return false;
        }

        $this->ymlData[$key] = $value;
        return true;
    }

    public function removeKey(string $key): void
    {
        if (array_key_exists($key, $this->ymlData)) {
            unset($this->ymlData[$key]);
        }
    }

    public function modifyValue(string $key, $newValue): void
    {
        if (!is_numeric($newValue) && !is_bool($newValue) && !is_array($newValue) && !is_string($newValue)) {
            return;
        }

        if (array_key_exists($key, $this->ymlData)) {
            $this->ymlData[$key] = $newValue;
        }
    }
}
