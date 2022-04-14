<?php

namespace App\Table;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;
use function Convert\consistentRead;
use function Convert\eventualRead;
use function Convert\kylobyte;
use function Convert\transactionalRead;

class ScanQueryDynamoItemTable extends DynamoTable
{
    private int $id = 0;

    private float $countBytes = 0;

    public function __construct(OutputInterface $output)
    {
        $this->setHeaderTitle("Read: Query / Scan");
        parent::__construct($output);
    }

    public function addDynamoItem(array $item) {
        $this->countBytes += $countBytes = sizeDynamo($item);

        $this->addRow([
            $this->id++,
            ...$this->getValuesColumns($item),
            sprintf('%.2f bytes',      $countBytes),
            sprintf('%.2f kylo bytes', kylobyte($countBytes)),
            sprintf('%.2f RCU',        eventualRead($countBytes)),
            sprintf('%.2f RCU',        consistentRead($countBytes)),
            sprintf("%.2f RCU",        transactionalRead($countBytes)),
        ]);
    }

    public function render()
    {
        $this->setHeaders(["ID", ...$this->properties, "Bytes", "KyloBytes", "Eventual Read", "Strong consistent Read", "Transactional Read"]);
        $this->addRow(new TableSeparator());
        $this->addRow([
            'Total',
            ...array_fill(0, count($this->properties), ''),
            sprintf('%.2f bytes', $this->countBytes),
            sprintf('%.2f kylo bytes', kylobyte($this->countBytes)),
            sprintf('%.2f RCU', eventualRead($this->countBytes, ceil: true)),
            sprintf('%.2f RCU', consistentRead($this->countBytes, ceil: true)),
            sprintf('%.2f RCU', transactionalRead($this->countBytes, ceil: true)),
        ]);

        parent::render();
    }
}