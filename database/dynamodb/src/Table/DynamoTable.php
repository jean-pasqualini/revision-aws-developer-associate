<?php

namespace App\Table;

use Symfony\Component\Console\Helper\Table;

abstract class DynamoTable extends Table
{
    protected array $properties = [];

    /**
     * @param array $item
     * @return array
     */
    protected function getValuesColumns(array $item): array
    {
        $values = [];
        foreach ($item as $key => $value) {
            if (!in_array($key, $this->properties)) {
                $this->properties[] = $key;
            }
            $values[] = substr($value['S'], 0, 10);
        }
        return $values;
    }
}