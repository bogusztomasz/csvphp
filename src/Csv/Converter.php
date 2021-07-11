<?php

declare(strict_types=1);

namespace Csv;

use Exception;
use Csv\Item;

class Converter
{

    const CSV_SEPARATOR = ';';
    const CSV_LINE_LENGTH = 0;

    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function processingFile(string $file)
    {
        try {
            $this->checkFile($file);
            $this->readingCsv($file);
        } catch (Exception $e) {
            $this->logger->log($e->getMessage());
        }
    }

    private function checkFile(string $file)
    {
        if (!file_exists($file)) {
            throw new Exception('File ' . $file . ' doesn\'t exist');
        }
    }

    private function readingCsv(string $file)
    {
        foreach ($this->walkOnFile($file) as $item) {
            echo $item . PHP_EOL;
        }
    }

    private function getColumns($fileHandler, string $file): array
    {

        $columns = fgetcsv($fileHandler, self::CSV_LINE_LENGTH, self::CSV_SEPARATOR);
        if ($columns === false) {
            throw new Exception('File ' . $file . ' is empty');
        }

        if (count($columns) === 1) {
            throw new Exception('Probably problem with a csv separator in the file: ' . $file);
        }

        return $columns;
    }

    private function walkOnFile($file)
    {
        $fileHandler = fopen($file, 'r');

        if (!$fileHandler) {
            throw new Exception('File ' . $file . ' cannot be read');
        }

        $this->logger->log('Processing file: ' . $file);
        
        $columns = $this->getColumns($fileHandler, $file);
        
        $lineNumber = 2;
        while (false !== ($data = fgetcsv($fileHandler, self::CSV_LINE_LENGTH, self::CSV_SEPARATOR))) {
            $item = new Item($columns, $data);
            $itemToString = (string) $item;
            $this->logger->log('LINE ' . $lineNumber . ': ' . $item);
            $lineNumber++;

            //displaying only correct items
            if ($item->hasError()) {
                continue;
            }

            yield $itemToString;
        }
    }
}
