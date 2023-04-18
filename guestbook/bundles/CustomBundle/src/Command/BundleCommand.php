<?php

namespace CustomBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'custombundle:command',
    description: 'Echo custom text',
    hidden: false
)]
class BundleCommand extends Command {

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Command added via bundle.');
        return Command::SUCCESS;
    }

}