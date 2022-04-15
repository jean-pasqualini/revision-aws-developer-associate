<?php

namespace App\Store;

use App\Helper\ConsumedCapacityTrait;
use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Output\OutputInterface;

class DynamoStore
{
    use ConsumedCapacityTrait;

    public function __construct(private DynamoDbClient $dynamoClient) {}

    public function storeItems(OutputInterface $output, string $table, array $items)
    {
        foreach ($items as $item) {
            if (count($items) > 0) {
                $output->writeln("sleep...");
            }
            $result = $this->dynamoClient->putItem([
                'TableName' => $table,
                'Item' => $item,
                'ReturnConsumedCapacity' => 'INDEXES',
                'ReturnItemCollectionMetrics' => 'SIZE',
                'ReturnValues' => 'ALL_OLD',
            ]);

            $this->showItemCollectionMetrics($result->toArray(), $output);
            $this->showConsumeCapacity($result->toArray(), $output);
        }
    }

    public function storeBatchItems(OutputInterface $output, string $table, array $items)
    {
        $batch = [];
        foreach ($items as $item) {
            $batch[] = [
                "PutRequest"=> [
                    "Item" => $item
                ]
            ];
        }

        $try = 1;
        do {
            $output->writeln("try n° ".$try.", count remainings = ".count($batch));
            $result = $this->dynamoClient->batchWriteItem([
                "RequestItems" => [
                    $table => $batch
                ],
                'ReturnConsumedCapacity' => 'INDEXES'
            ]);
            $this->showConsumeCapacity($result->toArray(), $output);
            $try++;
        } while (count($batch = ($result['UnprocessedItems']['revision-dynamo'] ?? [])) > 0 && $try <= 10);
    }
}