<?php

$character = [
    "CharacterName" => "Mario"
];

$character = grown($character, 4096);
$size = size($character);
$dynamoFormat = dynamoFormat($character);

require_once __DIR__."/template.php";

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

function dynamoFormat(array $data) {
    $dynamoFormat = [];
    foreach ($data as $key => $value) {
        $dynamoFormat[$key] = ["S" => $value];
    }


    return json_encode($dynamoFormat, JSON_PRETTY_PRINT);
}