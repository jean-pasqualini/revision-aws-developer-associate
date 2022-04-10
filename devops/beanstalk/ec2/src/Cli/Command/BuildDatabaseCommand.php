<?php

namespace Darkilliant\Cli\Command;

use Darkilliant\Database\Mysql;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BuildDatabaseCommand implements CommandInterface
{
    public function __construct(private Mysql $mysql) {}

    public function run(InputInterface $input, OutputInterface $output): void
    {
        $richOutput = new SymfonyStyle($input, $output);
        $this->mysql->createSchema();
        $richOutput->success("schema created");
    }
}