<?php

namespace Darkilliant\Cli\Middleware;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandMiddlewareInterface
{
    public function run(InputInterface $input, OutputInterface $output): void;
}