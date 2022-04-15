<?php

namespace {

    function grown(array $data, int $targetSize, $keyGenerated = "__generated")
    {
        $currentSize = size($data);
        $data[$keyGenerated] = str_pad("", $targetSize - (strlen($keyGenerated) + $currentSize), "#");

        return $data;
    }


    function size(array $data)
    {
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

    function sizeDynamo(?array $data)
    {
        if (null === $data) {
            return 1;
        }

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

    function dynamoFormat(array $data)
    {
        $dynamoFormat = [];
        foreach ($data as $key => $value) {
            $dynamoFormat[$key] = ["S" => $value];
        }


        return $dynamoFormat;
    }
}


namespace Convert {

    const ONE_BYTE = 1;
    # kB, le symbole du kilobyte (1 000 bytes ou 1 024 bytes selon les contextes ; voir l'article octet).
    # on l'apelle aussi le kilobyte binaire ou le kibibyte
    const ONE_KILOBYTE = ONE_BYTE * 1024;
    const FOUR_KILOBYTE = ONE_KILOBYTE * 4;
    const ONE_RCU_ON_CONSISTENT_READ = FOUR_KILOBYTE;
    const ONE_WCU_ON_STANDARD_WRITE = ONE_KILOBYTE;

    function kylobyte(float $bytes): float
    {
        return $bytes / ONE_KILOBYTE;
    }

    function consistentRead(float $bytes, bool $ceil = false): float
    {
        $result = $bytes / ONE_RCU_ON_CONSISTENT_READ;
        return $ceil ? ceil($result) : $result;
    }

    function standardWrite(float $bytes, bool $ceil = false): float
    {
        $result = $bytes / ONE_WCU_ON_STANDARD_WRITE;
        return $ceil ? ceil($result) : $result;
    }

    function eventualRead(float $bytes, bool $ceil = false) : float
    {
        return consistentRead($bytes, $ceil) / 2;
    }

    function transactionalRead(float $bytes, bool $ceil = false): float
    {
        return consistentRead($bytes, $ceil) * 2;
    }

    function transactionalWrite(float $bytes, bool $ceil = false): float
    {
        return standardWrite($bytes, $ceil) * 2;
    }
}