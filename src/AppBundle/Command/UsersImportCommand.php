<?php

namespace AppBundle\Command;

use AppBundle\Service\Importer\UsersImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UsersImportCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('users:import')
            ->setDescription('Imports users from csv file')
            ->setHelp('users:import -f <file>')

            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'File to import'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('file');

        if (empty($file)) {
            throw new \InvalidArgumentException('No file specified');
        }

        /** @var UsersImporter $importer */
        $importer = $this->getApplication()->getKernel()->getContainer()->get('import.users');

        $time = microtime(true);
        $importer->import($file);
        $time = (microtime(true) - $time) * 1000;

        $output->writeln(sprintf('Import is complete. (%d secs spent)', (int) round($time)));

        return 0;
    }
}