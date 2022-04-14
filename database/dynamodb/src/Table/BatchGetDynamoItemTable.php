<?php

namespace App\Table;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use function Convert\consistentRead;
use function Convert\eventualRead;
use function Convert\kylobyte;
use function Convert\transactionalRead;

class BatchGetDynamoItemTable extends DynamoTable
{
    private int $id = 0;

    private array $countsBytes = [];

    public function __construct(OutputInterface $output)
    {
        $this->setHeaderTitle("Read : BATCH GET / GET");
        parent::__construct($output);
    }

    public function addDynamoItem(array $item) {
        $this->countsBytes []= $countBytes = sizeDynamo($item);

        $this->addRow([
            $this->id++,
            ...$this->getValuesColumns($item),
            sprintf('%.2f bytes', $countBytes),
            sprintf('%.2f kylo bytes', kylobyte($countBytes)),
            sprintf('%.2f RCU', eventualRead($countBytes, ceil: true)),
            sprintf('%.2f RCU',consistentRead($countBytes, ceil: true)),
            sprintf("%.2f RCU", transactionalRead($countBytes, ceil: true)),
        ]);
    }

    public function render()
    {
        $this->setHeaders(["ID", ...$this->properties, "Bytes", "KyloBytes", "Eventual Read", "Strong consistent Read", "Transactional Read"]);
        $this->addRow(new TableSeparator());
        $this->addRow([
            'Total',
            ...array_fill(0, count($this->properties), ''),
            sprintf('%.2f bytes', array_sum($this->countsBytes)),
            sprintf('%.2f kylo bytes', kylobyte(array_sum($this->countsBytes))),
            sprintf('%.2f RCU', array_sum(array_map(fn($bytes) => eventualRead($bytes, ceil: true), $this->countsBytes))),
            sprintf('%.2f RCU', array_sum(array_map(fn($bytes) => consistentRead($bytes, ceil: true), $this->countsBytes))),
            sprintf('%.2f RCU', array_sum(array_map(fn($bytes) => transactionalRead($bytes, ceil: true), $this->countsBytes))),
        ]);

        parent::render();
    }
}