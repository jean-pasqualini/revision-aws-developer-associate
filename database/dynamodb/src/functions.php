<?php

function grown(array $data, int $targetSize)
{
    $keyGenerated = "__generated";
    $currentSize = size($data);

    $fill = str_pad("", $targetSize - (strlen($keyGenerated) + $currentSize), "#");

    $data[$keyGenerated] = $fill;

    return $data;
}

function size(array $data) {
    $sizeInBytes = 0;
    // 4kb = 4096 bytes

    foreach ($data as $key => $value) {
        $keyLength = strlen($key);
        $valueLength = strlen($value);
        $propertyLength = $keyLength + $valueLength;
        $sizeInBytes += $propertyLength;
    }

    return $sizeInBytes;
}

function sizeDynamo(array $data) {
    $sizeInBytes = 0;
    // 4kb = 4096 bytes

    foreach ($data as $key => $value) {
        $keyLength = strlen($key);
        $valueLength = strlen($value["S"]);
        $propertyLength = $keyLength + $valueLength;
        $sizeInBytes += $propertyLength;
    }

    return $sizeInBytes;
}

function dynamoFormat(array $data) {
    $dynamoFormat = [];
    foreach ($data as $key => $value) {
        $dynamoFormat[$key] = ["S" => $value];
    }


    return $dynamoFormat;
}