<?php

declare(strict_types=1);

namespace Csv;

abstract class ItemValidator
{

    private $_prefixMethodValid = 'Valid';
    private $idMinValue = 0;
    private $indeksMaxLength = 20;
    private $stanMinValue = 0;
    private $cenaMinValue = 0;
    private $nazwaMaxLength = 50;

    private array $errors = [];

    protected function getErrors(): array
    {
        return $this->errors;
    }

    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }

    protected function getPrefixMethodValid(): string
    {
        return $this->_prefixMethodValid;
    }


    protected function isProperty(string $property): bool
    {
        if (!property_exists($this, $property)) {
            $this->errors[] = 'Property ' . $property . 'doesn\'t exists';
            return false;
        }
        return true;
    }

    protected function checkingColumns(array $columns, array $data): bool
    {
        $dataColumnsNumber = count($data);
        if (count($columns) === $dataColumnsNumber) {
            return true;
        }
        $this->errors[] = 'Incorrect columns number (' . $dataColumnsNumber . ')';
        return false;
    }

    protected function idValid(): void
    {
        if ($this->id <= $this->idMinValue) {
            $this->errors[] = 'Incorrect \'id\' value (' . $this->id . ')';
        }
    }

    protected function indeksValid(): void
    {
        if (($length = mb_strlen($this->indeks)) > $this->indeksMaxLength) {
            $this->errors[] = 'Incorrect \'indeks\' length (' . $length . ')';
        }
    }

    protected function stanValid(): void
    {
        if ($this->stan < $this->stanMinValue) {
            $this->errors[] = 'Incorrect \'stan\' value (' . $this->stan . ')';
        }
    }

    protected function cenaValid(string $rawValue): void
    {
        if ($this->cena <= $this->cenaMinValue) {
            $this->errors[] = 'Incorrect \'cena\' value (' . $rawValue . ')';
        }
    }

    protected function nazwaValid(): void
    {
        if (($length = mb_strlen($this->nazwa)) > $this->nazwaMaxLength) {
            $this->errors[] = 'Incorrect \'nazwa\' length (' . $length . ')';
        }
    }
}
