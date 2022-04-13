<?php

namespace App\Helper;

use App\Component\StatComponent;
use Symfony\Component\Console\Output\OutputInterface;

trait ShowTableItemTrait
{
    public function createStatComponent(OutputInterface $output)
    {
        return new StatComponent($output);
    }
}