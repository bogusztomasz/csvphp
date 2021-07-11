<?php

declare(strict_types=1);

namespace Csv;

class Item extends ItemValidator
{

    public int $id = 0;
    public string $indeks = '';
    public int $stan = 0;
    public float $cena = .0;
    public string $nazwa = '';

    public function __construct(array $columns, array $data)
    {
        $this->setProperties($this->mergeData($columns, $data));
    }

    private function mergeData(array $columns, array $data): array
    {
        if ($this->checkingColumns($columns, $data)) {
            return array_combine($columns, $data);
        }
        return [];
    }

    private function setProperties(array $data): void
    {
        foreach ($data as $property => $value) {
            if ($this->isProperty($property)) {
                $this->setAndValidProperty($property, $value);
            }
        }
    }

    private function setAndValidProperty(string $property, string $value)
    {
        $this->setProperty($property, $value);
        $this->validProperty($property, $value);
    }

    private function setProperty(string $property, string $value): void
    {
        switch ($property) {
            case 'id':
            case 'stan':
                $this->$property = (int) $value;
                break;
            case 'indeks':
            case 'nazwa':
                $this->$property = trim($value);
                break;
            case 'cena':
                $this->$property = (float) filter_var(str_replace(',', '.', $value), FILTER_VALIDATE_FLOAT);
                break;
        }
    }

    private function validProperty(string $property, string $rawValue ): void
    {
        $validator = $property . $this->getPrefixMethodValid();
        if (method_exists($this, $validator)) {
            $this->$validator($rawValue);
        }
    }

    public function toArray(): array
    {
        $data = [
            $this->id => get_object_vars($this),
        ];
        return $data;
    }

    public function __toString(): string
    {

        if($this->hasError()){
            return implode(', ', $this->getErrors());
        }

        return $this->specifyFormatString();
    }

    /**
     * pattern:
     * //2 => (“id” => 2, “indeks” => “test”, “stan” => 2, “cena” => 22.3, “nazwa” => “nazwa test”)
     */
    private function specifyFormatString(): string
    {
        return str_replace(['{', '}', ':'], ['(', ')', ' => '], json_encode($this->toArray()));
    }

}
