<?php

namespace Smart\ParameterBundle\Command;

use Smart\ParameterBundle\Loader\ParameterLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class ParameterLoadCommand extends Command
{
    private ParameterLoader $parameterLoader;

    public function __construct(ParameterLoader $parameterLoader)
    {
        $this->parameterLoader = $parameterLoader;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('smart:parameter:load')
            ->setDescription("Execute a loading of every parameters in the database.")
            ->setHelp(<<<EOT
The <info>%command.name%</info> command executes a loading of every parameters in the database:

    <info>%command.full_name%</info>

Detail behavior on load:

- If a parameter isn't found in the database by his code, it will be inserted.
- If a parameter is found in the database by his code, his help attribut will be updated but the value will remain the same.
- If an existing parameter is not found in the configuration it will be deleted from the database.

EOT
            )
            ->addOption('dry-run', null, InputOption::VALUE_NONE, "Simulates the result of the command without making any changes to the database")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('SmartParameterBundle');
        $io->text('Parameters are being loaded.');

        $dryRun = $input->getOption('dry-run');
        $logs = $this->parameterLoader->load($dryRun);

        $io->text('Execution results:');
        $io->listing([
            "$logs[nb_inserted] parameters inserted.",
            "$logs[nb_updated] parameters updated.",
            "$logs[nb_skipped] parameters skipped.",
            "$logs[nb_deleted] parameters deleted.",
        ]);

        if ($dryRun) {
            $io->text("<info>The command has dry-run option, so no change was done in the database.</info>");
        }

        return 0;
    }
}
