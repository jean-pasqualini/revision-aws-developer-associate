<?php

namespace App\Command;

use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AddCommand extends Command
{
    const ONE_BYTE = 1;
    const ONE_KILOBYTE = self::ONE_BYTE * 1024;
    const ONE_CONSISTENT_READ = self::ONE_KILOBYTE * 4;
    const ONE_EVENTUAL_READ = self::ONE_CONSISTENT_READ * 2;
    const CHARACTERS = [
        'Mario', 'Luigi', 'Sonic', 'Wario', 'Browser', 'Danielo', 'Thomas', 'Jules', 'Robin', 'Minecraft',
        'Dragon', 'Harry', 'Bilbon', 'Furet', 'Zeus', 'Bioshock', 'Pinball', 'Tea', 'Cofee', 'Blue',
    ];

    private Environment $twig;
    private DynamoDbClient $dynamoClient;

    public function __construct(string $name = null)
    {
        $this->twig = new Environment(new FilesystemLoader([__DIR__."/../template"]));
        $this->dynamoClient = new DynamoDbClient([
            'region' => 'eu-west-3',
            'version' => '2012-08-10',
        ]);
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName("add");
        $this->addArgument("size", InputArgument::REQUIRED, "target size in bytes (1024 bytes = 1kb)");
        $this->addOption("nb-lines", null, InputOption::VALUE_REQUIRED, "size dispatch in several lines", 1);
        $this->addOption('size-format', null, InputOption::VALUE_REQUIRED, 'format (kb = kylobyte, b = byte, er = eventual read, cr = consistent read)', 'kb');
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "dry run mode");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $sizeForamt = $input->getOption('size-format');
        $targetSize = intval($input->getArgument("size"));
        if ($sizeForamt == 'kb') {
            $targetSize *= self::ONE_KILOBYTE;
        }
        if ($sizeForamt == 'er') {
            $targetSize = $targetSize * self::ONE_EVENTUAL_READ;
        }
        if ($sizeForamt == 'cr') {
            $targetSize = $targetSize * self::ONE_CONSISTENT_READ;
        }


        $nbLines = intval($input->getOption('nb-lines'));

        $table = $symfonyStyle->createTable();
        $table->setHeaderTitle('info');
        $table->setHeaders(['ID', 'Size (b)', 'Size (kb)', 'eventual reads', 'consistent reads']);

        $countBytes = 0;
        $countKylobytes = 0;
        $countEventualReads = 0;
        $countConsistentReads = 0;

        for ($i = 1; $i <= $nbLines; $i++) {
            list($lineBytes, $lineKylobytes, $lineEventualReads, $lineConsistentReads) = $this->item(
                $table,
                $i,
                $input->getOption('dry-run'),
                self::CHARACTERS[$i-1],
                $nbLines,
                $targetSize
            );

            $countBytes += $lineBytes;
            $countKylobytes += $lineKylobytes;
            $countEventualReads += $lineEventualReads;
            $countConsistentReads += $lineConsistentReads;
        }

        $table->addRow(new TableSeparator());
        $table->addRow([
            'Total',
            sprintf('%d bytes', $countBytes),
            sprintf('%d kylo bytes', $countKylobytes),
            sprintf('%d eventual read', ceil($countEventualReads)),
            sprintf('%d consistent read',ceil($countConsistentReads)),
        ]);



        $table->render();

        return 0;
    }

    private function item(Table $table, $i, $isDryRun, $characterName, $nbLines, $targetSize)
    {
        $characterTargetSize = $targetSize / $nbLines;

        $character = grown([
            "CharacterName" => $characterName,
        ], $characterTargetSize);
        $dynamoFormat = dynamoFormat($character);

        $countBytes = size($character);
        $countKyloBytes = $countBytes / self::ONE_KILOBYTE;
        $countEventualRead = $countBytes / self::ONE_EVENTUAL_READ;
        $countConsistentRead = $countBytes / self::ONE_CONSISTENT_READ;


        $table->addRow([
            $i.'. '.$character['CharacterName'],
            sprintf('%d bytes', $countBytes),
            sprintf('%d kylo bytes', $countKyloBytes),
            sprintf('%d eventual read', $countEventualRead),
            sprintf('%d consistent read',$countConsistentRead),
        ]);

        if (!$isDryRun) {
            $this->dynamoClient->putItem([
                'TableName' => 'revision-dynamo',
                'Item' => $dynamoFormat,
            ]);
        }

        return [$countBytes, $countKyloBytes, $countEventualRead, $countConsistentRead];
    }
}