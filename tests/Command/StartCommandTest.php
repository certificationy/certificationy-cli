<?php

/*
 * This file is part of the Certificationy CLI application.
 *
 * (c) Vincent Composieux <vincent.composieux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Command;

use Certificationy\Cli\Command\StartCommand;
use Certificationy\Loaders\YamlLoader as Loader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

/**
 * StartCommandTest
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class StartCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StartCommand
     */
    private $command;

    /**
     * @var string config filepath
     */
    private $configFile;

    /**
     * @var Loader
     */
    private $yamlLoader;

    public function setUp(): void
    {
        $app = new Application();
        $app->add(new StartCommand());
        $this->command = $app->find('start');
        $this->configFile = $this->getTestsFolder() . 'config_test.yml';
        $paths = Yaml::parse(file_get_contents($this->configFile));

        $this->yamlLoader = new Loader($paths);
    }

    public function testCanListCategories()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            '-l' => true,
            '-c' => $this->configFile,
        ]);

        $output = $commandTester->getDisplay();

        self::assertMatchesRegularExpression('/A/', $output);
        self::assertCount(count($this->yamlLoader->categories()) + 1, explode("\n", $output));
    }

    public function testCanGetQuestions()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->setInputs(array_fill(0, 20, '0'));
        $commandTester->execute([
            'command' => $this->command->getName(),
            'categories' => ['B'],
            '-c' => $this->configFile,
        ]);

        $output = $commandTester->getDisplay();
        self::assertMatchesRegularExpression('/B/', $output);
        self::assertMatchesRegularExpression('/Starting a new set of 3 questions/', $commandTester->getDisplay());
    }

    public function testCanHideInformationAboutMultipleChoice()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->setInputs([0]);
        $commandTester->execute([
            'command' => $this->command->getName(),
            '--hide-multiple-choice' => null,
            '--number' => 1,
            '-c' => $this->configFile,
        ]);

        $output = $commandTester->getDisplay();
        self::assertDoesNotMatchRegularExpression('/This question IS( NOT)? multiple choice/', $output);
    }

    public function testCanUseTrainingMode()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->setInputs([0]);
        $commandTester->execute([
            'command' => $this->command->getName(),
            '--hide-multiple-choice' => null,
            '--number' => 1,
            '--training' => true,
            '-c' => $this->configFile,
        ]);

        $commandTester->setInputs([0]);
        $output = $commandTester->getDisplay();

        self::assertMatchesRegularExpression('/| Question | Correct answer | Result | Help |/', $output);
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    private function getTestsFolder()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }
}
