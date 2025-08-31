<?php

declare(strict_types=1);

namespace App\Command;

use App\Temporal\Runtime\RuntimeInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: "temporal:runtime:run")]
class RuntimeCommand extends Command
{
    /**
     * @param RuntimeInterface $runtime
     */
    public function __construct(private RuntimeInterface $runtime)
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runtime->run();

        return Command::SUCCESS;
    }
}
