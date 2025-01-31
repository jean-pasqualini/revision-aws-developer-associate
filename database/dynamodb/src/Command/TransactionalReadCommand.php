<?php

namespace App\Command;

use App\Helper\ConsumedCapacityTrait;
use App\Table\BatchGetDynamoItemTable;
use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionalReadCommand extends Command
{
    private DynamoDbClient $client;

    use ConsumedCapacityTrait;

    public function __construct(string $name = null)
    {
        $this->client = new DynamoDbClient([
            'region' => 'eu-west-3',
            'version' => '2012-08-10',
            'retries' => 3,
        ]);
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName("transactional-read");
        parent::configure(); // TODO: Change the autogenerated stub
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->client->transactGetItems([
            'ReturnConsumedCapacity' => 'INDEXES',
            "TransactItems" => [
                [
                    "Get" => [
                        "TableName" => "revision-dynamo",
                        "Key" => [
                            "CharacterName" => ["S" => "Mario"],
                            "Power" => ["S" => "Fire"],
                        ]
                    ]
                ],
                [
                    "Get" => [
                        "TableName" => "revision-dynamo",
                        "Key" => [
                            "CharacterName" => ["S" => "Luigi"],
                            "Power" => ["S" => "Fire"],
                        ]
                    ]
                ]
            ]
        ]);

        $table = new BatchGetDynamoItemTable($output);
        foreach ($result["Responses"] as $response) {
            if (isset($response["Item"])) {
                $table->addDynamoItem($response["Item"]);
            }
        }
        $table->render();
        $this->showConsumeCapacity($result->toArray(), $output);

        return 0;
    }
}