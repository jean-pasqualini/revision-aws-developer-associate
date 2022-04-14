<?php

namespace App\Table;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use function Convert\kylobyte;
use function Convert\standardWrite;
use function Convert\transactionalWrite;

class BatchPutDynamoItemTable extends DynamoTable
{
    private int $id = 0;

    private array $countsBytes = [];

    public function __construct(OutputInterface $output)
    {
        $this->setHeaderTitle("Write: BATCH PUT / PUT");
        parent::__construct($output);
    }

    public function addDynamoItem(array $item) {
        $this->countsBytes[] = $countBytes = sizeDynamo($item);

        $this->addRow([
            $this->id++,
            ...$this->getValuesColumns($item),
            sprintf('%.2f bytes', $countBytes),
            sprintf('%.2f kylo bytes', kylobyte($countBytes)),
            sprintf('%.2f WCU', standardWrite($countBytes, ceil: true)),
            sprintf('%.2f WCU', transactionalWrite($countBytes, ceil: true)),
        ]);
    }

    public function render()
    {
        $this->setHeaders(["ID", ...$this->properties, "Bytes", "KyloBytes", "Write", "Transactional write"]);
        $this->addRow(new TableSeparator());
        $this->addRow([
            'Total',
            ...array_fill(0, count($this->properties), ''),
            sprintf('%.2f bytes', array_sum($this->countsBytes)),
            sprintf('%.2f kylo bytes', kylobyte(array_sum($this->countsBytes))),
            sprintf('%.2f WCU', array_sum(array_map(fn($bytes) => standardWrite($bytes, ceil: true), $this->countsBytes))),
            sprintf('%.2f WCU', array_sum(array_map(fn($bytes) => transactionalWrite($bytes, ceil: true), $this->countsBytes))),
        ]);

        parent::render();
    }
}