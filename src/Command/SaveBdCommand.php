<?php

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\ZipArchive;



class SaveBdCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bd:save';
    protected static $defaultDescription = 'Save BD';
    private $client;
    private $container;
    private $output;

    protected function configure(): void
    {
        $this->setHelp('Sauvegarde la base de donnÃƒÂ©es');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->output = $output;
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return Command::SUCCESS;
        }


        $output->writeln([
            'Sauvegarde start',
            '============',
            '',
        ]);

        $dumpCommand = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            'login4676',
            'FkDFRdkDYBfoDcU',
            'dbjeux',
            '/datas/dbjeux.sql'
        );

        /*$zip = new ZipArchive();


        // open zip
        if ($zip->open($zipName, \ZipArchive::CREATE) !== true) {
            throw new FileException('Zip file could not be created/opened.');
        }

        // add to zip
        $zip->addFile($dumpCommand->getRealpath(), basename($dumpCommand->getRealpath()));

        // close zip
        if(!$zip->close()) {
            throw new FileException('Zip file could not be closed.');
        }*/

        $process = Process::fromShellCommandline($dumpCommand);
        $process->run();
    

        $output->writeln([
            'Sauvegarde end',
            '============',
            '',
        ]);

        $this->release();

        return Command::SUCCESS;

        
    }
}

