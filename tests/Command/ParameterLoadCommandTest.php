<?php

namespace Smart\ParameterBundle\Tests\Command;

use Doctrine\ORM\EntityManagerInterface;
use Smart\ParameterBundle\Command\ParameterLoadCommand;
use Smart\ParameterBundle\Entity\Parameter;
use Smart\ParameterBundle\Tests\AbstractWebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 *
 * vendor/bin/phpunit tests/Command/ParameterLoadCommandTest.php
 */
class ParameterLoadCommandTest extends AbstractWebTestCase
{
    private ?ParameterLoadCommand $command;
    private ?EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $container = self::$container;
        $this->command = $container->get(ParameterLoadCommand::class);
    }

    /**
     * @dataProvider executeProvider
     */
    public function testExecute(string $fixture, array $logs): void
    {
        # check tests/config_test.php for parameters configuration reference
        $this->loadFixtureFiles([$this->getFixtureDir() . "/Command/ParameterLoadCommand/$fixture.yaml"]);

        $application = new Application(self::$kernel);
        $command = $application->find('smart:parameter:load');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("* $logs[nb_inserted] parameters inserted.", $output);
        $this->assertStringContainsString("* $logs[nb_updated] parameters updated.", $output);
        $this->assertStringContainsString("* $logs[nb_skipped] parameters skipped.", $output);
        $this->assertStringContainsString("* $logs[nb_deleted] parameters deleted.", $output);
        // they are 2 parameters in the config_test.yml so for every fixtures the count value after execute mush be 2
        $this->assertSame(2, $this->getParameterRepository()->count([]));
    }

    public function executeProvider(): array
    {
        return [
            'test_output_nb_inserted' => ['parameter_to_insert', [
                "nb_inserted" => 2,
                "nb_updated" => 0,
                "nb_skipped" => 0,
                "nb_deleted" => 0,
            ]],
            'test_output_nb_updated' => ['parameter_to_update', [
                "nb_inserted" => 0,
                "nb_updated" => 2,
                "nb_skipped" => 0,
                "nb_deleted" => 0,
            ]],
            'test_output_nb_skipped' => ['parameter_to_skip', [
                "nb_inserted" => 0,
                "nb_updated" => 0,
                "nb_skipped" => 2,
                "nb_deleted" => 0,
            ]],
            'test_output_nb_deleted' => ['parameter_to_delete', [
                "nb_inserted" => 2,
                "nb_updated" => 0,
                "nb_skipped" => 0,
                "nb_deleted" => 3,
            ]],
            'test_output_with_batch_flush_on_existing' => ['parameter_with_batch_flush_on_existing', [
                "nb_inserted" => 2,
                "nb_updated" => 0,
                "nb_skipped" => 0,
                "nb_deleted" => 20,
            ]],
            'test_output_with_batch_flush_on_inserting' => ['parameter_with_batch_flush_on_inserting', [
                "nb_inserted" => 2,
                "nb_updated" => 0,
                "nb_skipped" => 0,
                "nb_deleted" => 19,
            ]],
        ];
    }

    public function testExecuteDryRun(): void
    {
        $application = new Application(self::$kernel);
        $command = $application->find('smart:parameter:load');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['--dry-run' => true]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("The command has dry-run option, so no change was done in the database.", $output);
        $this->assertSame(0, $this->getParameterRepository()->count([]));
    }
}
