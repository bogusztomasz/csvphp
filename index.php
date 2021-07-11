<?php

require 'vendor/autoload.php';

use \Csv\Converter;
use \Csv\Logger;

$converter = new Converter(Logger::getInstance());
$converter->processingFile(__DIR__ . '/data.csv');
$converter->processingFile(__DIR__ . '/data_columns_switched.csv');
