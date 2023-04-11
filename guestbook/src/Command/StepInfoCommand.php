<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'app:step:info',
    description: 'command',
)]
class StepInfoCommand extends Command
{

    public function __construct(
        private CacheInterface $cache
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $step = $this->cache->get('app.current_step', function ($item) {
            $process = new Process(['git', 'status']);
            $process->mustRun();
            $item->expiresAfter(30);        
            
            return $process->getOutput();
        });

        $output->writeln($step);

        return Command::SUCCESS;
    }
}
