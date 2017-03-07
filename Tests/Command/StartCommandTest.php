<?php

/*
 * This file is part of the Certificationy CLI application.
 *
 * (c) Vincent Composieux <vincent.composieux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Certificationy\Cli\Tests\Command;

use Certificationy\Certification\Loader;
use Certificationy\Cli\Command\StartCommand;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * StartCommandTest
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class StartCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var StartCommand
     */
    private $command;

    /**
     * @var string config filepath
     */
    private $configFile;

    public function setUp()
    {
        $app = new Application();
        $app->add(new StartCommand());
        $this->command = $app->find('start');
        $this->configFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.yml';
    }

    public function testCanListCategories()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array(
            'command' => $this->command->getName(),
            '-l' => true
        ));

        $output = $commandTester->getDisplay();
        $this->assertRegExp('/Templating/', $output);
        $this->assertCount(count(Loader::getCategories($this->configFile)) + 1, explode("\n", $output));
    }

    public function testCanGetQuestions()
    {
        $helper = $this->command->getHelper('question');
        $helper->setInputStream($this->getInputStream(str_repeat("0\n", 20)));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array(
            'command' => $this->command->getName(),
            'categories' => ['Templating'],
        ));

        $output = $commandTester->getDisplay();
        $this->assertRegExp('/Twig/', $output);
        $this->assertRegExp('/Starting a new set of 20 questions/', $commandTester->getDisplay());
    }

    public function testCanHideInformationAboutMultipleChoice()
    {
        $helper = $this->command->getHelper('question');
        $helper->setInputStream($this->getInputStream(str_repeat("0\n", 1)));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array(
            'command' => $this->command->getName(),
            '--hide-multiple-choice' => null,
            '--number' => 1,
        ));

        $output = $commandTester->getDisplay();
        $this->assertNotRegExp('/This question IS( NOT)? multiple choice/', $output);
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);
        return $stream;
    }

}
