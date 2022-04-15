<?php

namespace App\Command;

use App\Component\StatComponent;
use App\Helper\ConsumedCapacityTrait;
use App\Helper\ShowTableItemTrait;
use App\Store\DynamoStore;
use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCommand extends Command
{
    const ONE_BYTE = 1;
    const ONE_KILOBYTE = self::ONE_BYTE * 1024; // 1024
    const ONE_WRITE = self::ONE_KILOBYTE;
    const ONE_CONSISTENT_READ = self::ONE_KILOBYTE * 4;
    const ONE_EVENTUAL_READ = self::ONE_CONSISTENT_READ * 2;
    const CHARACTERS = [
        'Mario', 'Luigi', 'Sonic', 'Wario', 'Browser', 'Danielo', 'Thomas', 'Jules', 'Robin', 'Minecraft',
        'Dragon', 'Harry', 'Bilbon', 'Furet', 'Zeus', 'Bioshock', 'Pinball', 'Tea', 'Cofee', 'Blue',
    ];

    use ConsumedCapacityTrait;
    use ShowTableItemTrait;

    private DynamoDbClient $dynamoClient;
    private DynamoStore $store;

    public function __construct(string $name = null)
    {
        $this->dynamoClient = new DynamoDbClient([
            'region' => 'eu-west-3',
            'version' => '2012-08-10',
            'retries' => 3,
        ]);
        $this->store = new DynamoStore($this->dynamoClient);
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName("add");
        $this->addOption('batch');
        $this->addArgument("size", InputArgument::REQUIRED, "target size in bytes (1024 bytes = 1kb)");
        $this->addOption("nb-lines", null, InputOption::VALUE_REQUIRED, "size dispatch in several lines", 1);
        $this->addOption('size-format', null, InputOption::VALUE_REQUIRED, 'format (kb = kylobyte, b = byte, er = eventual read, cr = consistent read)', 'kb');
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "dry run mode");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $sizeFormat = $input->getOption('size-format');
        $targetSize = intval($input->getArgument("size"));
        if ($sizeFormat == 'kb') {
            $targetSize *= self::ONE_KILOBYTE;
        }
        if ($sizeFormat == 'er') {
            $targetSize = $targetSize * self::ONE_EVENTUAL_READ;
        }
        if ($sizeFormat == 'cr') {
            $targetSize = $targetSize * self::ONE_CONSISTENT_READ;
        }

        $result = $this->dynamoClient->describeTable([
            'TableName' => 'revision-dynamo',
        ]);

        $writeCapacityUnits = $result["Table"]['ProvisionedThroughput']['WriteCapacityUnits'];
        $tableSize = $result['TableSizeBytes'];
        $itemCount = $result['ItemCount'];

        $symfonyStyle->horizontalTable(
            ["Capacity", "TableSize", "Item count"],
            [[
                sprintf("%d WCU", $writeCapacityUnits),
                sprintf("%d Bytes", $tableSize),
                sprintf("%d Items", $itemCount)
            ]]
        );

        $nbLines = (int)$input->getOption('nb-lines');
        $isDryRun = $input->getOption('dry-run');


        $statComponent = new StatComponent($output);
        $items = [];

        for ($i = 1; $i <= $nbLines; $i++) {
            $characterTargetSize = $targetSize / $nbLines;
            $character = grown([
                "CharacterName" => self::CHARACTERS[$i-1], "Power" => "Fire", "Friend" => "Jules"
            ], $characterTargetSize);
            $dynamoFormat = dynamoFormat($character);
            $statComponent->addDynamoItem($dynamoFormat);
            $items[] = $dynamoFormat;
        }

        $statComponent->render();

        if (!$isDryRun) {
            if ($input->getOption('batch')) {
                $this->store->storeBatchItems($output, "revision-dynamo", $items);
            } else {
                $this->store->storeItems($output, "revision-dynamo", $items);
            }
        }

        return 0;
    }
}