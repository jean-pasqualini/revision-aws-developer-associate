<?php

namespace App\Component;

use App\Table\BatchGetDynamoItemTable;
use App\Table\BatchPutDynamoItemTable;
use App\Table\ScanQueryDynamoItemTable;
use Symfony\Component\Console\Output\OutputInterface;

class StatComponent
{
    private BatchGetDynamoItemTable $batchGetTable;
    private BatchPutDynamoItemTable $batchPutTable;
    private ScanQueryDynamoItemTable $scanQueryTable;

    public function __construct(OutputInterface $output) {
        $this->batchGetTable = new BatchGetDynamoItemTable($output);
        $this->batchPutTable = new BatchPutDynamoItemTable($output);
        $this->scanQueryTable = new ScanQueryDynamoItemTable($output);
    }

    public function addDynamoItem(array $item)
    {
        $this->batchGetTable->addDynamoItem($item);
        $this->batchPutTable->addDynamoItem($item);
        $this->scanQueryTable->addDynamoItem($item);
    }

    public function render()
    {
        $this->scanQueryTable->render();
        $this->batchGetTable->render();
        $this->batchPutTable->render();
    }
}