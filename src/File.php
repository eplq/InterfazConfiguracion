<?php

namespace ITEC\DAW\EPL\Configuracion;

abstract class File
{
    protected string $filename;
    protected string $fileData;

    public function openFile(string $filename): void
    {
        $this->filename = $filename;

        if (!file_exists($this->filename)) {
            touch($this->filename);
        }

        $this->fileData = file_get_contents($this->filename);
        $this->readFile();
    }

    public function closeFile(): void
    {
        $this->saveFile();
        file_put_contents($this->filename, $this->fileData);
    }

    abstract protected function readFile(): void;
    abstract protected function saveFile(): void;
}
