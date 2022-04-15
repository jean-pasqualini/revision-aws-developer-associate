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
    protected function getValuesColumns(?array $item): array
    {
        if (null === $item) {
            return array_fill(0, count($this->properties), '');
        }

        // Discover
        foreach ($item as $key => $value) {
            if (!in_array($key, $this->properties)) {
                $this->properties[] = $key;
            }
        }

        // Fill the known
        $values = [];
        foreach ($this->properties as $position => $key) {
            if(isset($item[$key])) {
                $values[$position] = substr($item[$key]["S"], 0, 10);
            } else {
                $values[$position] = "";
            }
        }

        return $values;
    }
}