<?php

namespace Darkilliant\Cli\Middleware;

use Darkilliant\Cli\Command\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RoutingCommandMiddleware implements CommandMiddlewareInterface
{
    public function __construct(private array $commands) {}

    public function run(InputInterface $input, OutputInterface $output): void
    {
        $cmd = $this->commands[$input->getFirstArgument()] ?? null;
        if (null === $cmd) {
            $output->writeln("<error>command not found</error>");
            return;
        }
        if (!$cmd instanceof CommandInterface) {
            $output->writeln("<error>command is not implement CommandInterface</error>");
            return;
        }

        $cmd->run($input, $output);
        // TODO: Implement run() method.
    }
}