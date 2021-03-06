<?php

namespace ITEC\DAW\EPL\Configuracion;

interface IConfiguracion
{
    public function openFile(string $filename): void;
    public function closeFile(): void;

    public function readValue(string $key);
    public function removeKey(string $key): void;
    public function modifyValue(string $key, $newValue): void;
    public function createValue(string $key, $value): bool;
}
