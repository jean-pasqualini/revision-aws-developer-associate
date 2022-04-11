<?php

namespace App\Helper;

trait ConsumedCapacityTrait
{
    public function showConsumeCapacity(array $result, $symfonyStyle)
    {
        $consumedCapacity = $result['ConsumedCapacity'];

        $table = $symfonyStyle->createTable();
        $table->setHeaderTitle('Consumed capacity');
        $table->setHeaders(['Table name', 'units']);
        $table->addRow([$consumedCapacity['TableName'], $consumedCapacity['CapacityUnits']]);
        $table->render();
    }
}