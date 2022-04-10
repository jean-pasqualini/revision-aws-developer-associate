<?php

namespace Darkilliant\Cli\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandInterface
{
    public function run(InputInterface $input, OutputInterface $output): void;
}