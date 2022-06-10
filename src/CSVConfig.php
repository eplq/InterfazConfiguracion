<?php

namespace ITEC\DAW\EPL\Configuracion;

use League\Csv\Reader;
use League\Csv\Writer;

class CSVConfig extends File implements IConfiguracion
{
    private array $header = ["key", "value"];
    private array $csvData;

    protected function readFile(): void
    {
        $csvReader = Reader::createFromString($this->fileData);
        $csvReader->setHeaderOffset(0); // La primera fila es la cabecera

        // https://csv.thephpleague.com/9.0/reader/#csv-records
        $records = $csvReader->getRecords($this->header);
        $this->csvData = [];
        foreach ($records as $record) {
            $this->csvData[$record[$this->header[0]]] = $record[$this->header[1]];
        }
    }

    protected function saveFile(): void
    {
        $csvWriter = Writer::createFromString();
        $csvWriter->insertOne($this->header);

        foreach ($this->csvData as $key => $value) {
            $record = [];
            $record[$this->header[0]] = $key;
            $record[$this->header[1]] = $value;

            $csvWriter->insertOne($record);
        }

        $this->fileData = $csvWriter->toString();
    }

    public function readValue(string $key): string | float | int | bool | null
    {
        if (array_key_exists($key, $this->csvData)) {
            return $this->csvData[$key];
        }

        return null;
    }

    public function createValue(string $key, $value): bool
    {
        if (!is_numeric($value) && !is_bool($value) && !is_string($value)) {
            return false;
        }

        if (array_key_exists($key, $this->csvData)) {
            return false;
        }

        $this->csvData[$key] = $value;

        return true;
    }

    public function removeKey(string $key): void
    {
        if (array_key_exists($key, $this->csvData)) {
            unset($this->csvData[$key]);
        }
    }

    public function modifyValue(string $key, $newValue): void
    {
        if (!is_numeric($newValue) && !is_bool($newValue) && !is_string($newValue)) {
            return;
        }

        if (array_key_exists($key, $this->csvData)) {
            $this->csvData[$key] = $newValue;
        }
    }
}
