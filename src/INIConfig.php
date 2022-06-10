<?php

namespace ITEC\DAW\EPL\Configuracion;

use ITEC\DAW\EPL\Configuracion\File;
use ITEC\DAW\EPL\Configuracion\IConfiguracion;

class INIConfig extends File implements IConfiguracion
{
    private array $iniData;

    public function readFile(): void
    {
        $this->iniData = parse_ini_string($this->fileData, true);
    }

    public function saveFile(): void
    {
        // https://stackoverflow.com/a/5695202

        $res = array();
        foreach ($this->iniData as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";

                foreach ($val as $skey => $sval) {
                    $res[] = "$skey = " . (is_numeric($sval) ? $sval : '"' . $sval . '"');
                }
            } else {
                $res[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
            }
        }

        $this->fileData = implode("\n", $res);
    }

    public function readValue(string $key): string | float | int | bool | null
    {
        if (array_key_exists($key, $this->iniData)) {
            return $this->iniData[$key];
        }

        return null;
    }

    public function createValue(string $key, $value): bool
    {
        if (!is_numeric($value) && !is_bool($value) && !is_string($value)) {
            return false;
        }

        if (array_key_exists($key, $this->iniData)) {
            return false;
        }

        $this->iniData[$key] = $value;

        return true;
    }

    public function removeKey(string $key): void
    {
        if (array_key_exists($key, $this->iniData)) {
            unset($this->iniData[$key]);
        }
    }

    public function modifyValue(string $key, $newValue): void
    {
        if (!is_numeric($newValue) && !is_bool($newValue) && !is_string($newValue)) {
            return;
        }

        if (array_key_exists($key, $this->iniData)) {
            $this->iniData[$key] = $newValue;
        }
    }
}
