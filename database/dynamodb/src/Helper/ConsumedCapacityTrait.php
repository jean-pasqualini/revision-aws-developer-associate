<?php

namespace App\Helper;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

trait ConsumedCapacityTrait
{
    public function showItemCollectionMetrics(array $result, OutputInterface $output)
    {
        $output->writeln("debug metrics : ".json_encode($result['ItemCollectionMetrics']));
    }

    public function showConsumeCapacity(array $result, OutputInterface $output)
    {
        $output->writeln("debug capacity : ".json_encode($result['ConsumedCapacity']));
        $consumedCapacity = $result['ConsumedCapacity'][0] ?? $result['ConsumedCapacity'];

        $table = new Table($output);
        $table->setHeaderTitle('Consumed capacity');
        $table->setHeaders(['Name', 'units']);
        $table->addRow(["TABLE ".$consumedCapacity['TableName'], $consumedCapacity["Table"]['CapacityUnits']]);
        foreach (($consumedCapacity["LocalSecondaryIndexes"] ?? []) as $indexName => $stat) {
            $table->addRow(["LSI ".$indexName, $stat["CapacityUnits"]]);
        }
        foreach (($consumedCapacity["GlobalSecondaryIndexes"] ?? []) as $indexName => $stat) {
            $table->addRow(["GSI ".$indexName, $stat["CapacityUnits"]]);
        }
        $table->addRow(new TableSeparator());
        $table->addRow(["Total", $consumedCapacity['CapacityUnits']]);
        $table->render();
    }
}