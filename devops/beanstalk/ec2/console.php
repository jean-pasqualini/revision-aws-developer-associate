<?php

namespace App;

require __DIR__.'/vendor/autoload.php';

use Darkilliant\Cli\Command\BuildDatabaseCommand;
use Darkilliant\Cli\Middleware\RoutingCommandMiddleware;
use Darkilliant\Database\Mysql;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$command = new RoutingCommandMiddleware([
    "schema" => new BuildDatabaseCommand(new Mysql())
]);
$command->run(new ArgvInput(), new ConsoleOutput());
