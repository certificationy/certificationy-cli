<?php

/*
 * This file is part of the Certificationy CLI application.
 *
 * (c) Vincent Composieux <vincent.composieux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Certificationy\Cli\Command;

use Certificationy\Certification\Loader;
use Certificationy\Certification\Set;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class StartCommand
 *
 * This is the command to start a new questions set
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class StartCommand extends Command
{
    /**
     * @var integer
     */
    const WORDWRAP_NUMBER = 80;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Starts a new question set')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'How many questions do you want?', 20)
            ->addOption('list', 'l', InputOption::VALUE_NONE, 'List categories')
            ->addOption("training", null, InputOption::VALUE_NONE, "Training mode: the solution is displayed after each question")
            ->addOption('show-multiple-choice', null, InputOption::VALUE_OPTIONAL, 'Should we tell you when the question is multiple choice?', true)
            ->addArgument('categories', InputArgument::IS_ARRAY, 'Which categories do you want (separate multiple with a space)', array())
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL, 'Use custom config', null)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->path($input->getOption('config'));
        if ($input->getOption('list')) {
            $output->writeln(Loader::getCategories($config));
            return ;
        }

        $categories = $input->getArgument('categories');
        $number     = $input->getOption('number');

        $set = Loader::init($number, $categories, $config);

        if ($set->getQuestions()) {
            $output->writeln(
                sprintf('Starting a new set of <info>%s</info> questions (available questions: <info>%s</info>)', count($set->getQuestions()), Loader::count(array(), $this->path()))
            );

            $this->askQuestions($set, $input, $output);
            $this->displayResults($set, $output);
        } else {
            $output->writeln('<error>✗</error> No questions can be found.');
        }
    }

    /**
     * Ask questions
     *
     * @param Set             $set    A Certificationy questions Set instance
     * @param InputInterface  $input  A Symfony Console input instance
     * @param OutputInterface $output A Symfony Console output instance
     */
    protected function askQuestions(Set $set, InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');
        $showMultipleChoice = $input->getOption('show-multiple-choice');
        $questionCount = 1;

        foreach($set->getQuestions() as $i => $question) {
            $choiceQuestion = new ChoiceQuestion(
                sprintf(
                    'Question <comment>#%d</comment> [<info>%s</info>] %s'.
                    ($showMultipleChoice === true ? "\n" . 'This question <comment>'.($question->isMultipleChoice() === true ? 'IS' : 'IS NOT')."</comment> multiple choice." : ""),
                    $questionCount++, $question->getCategory(), $question->getQuestion()
                ),
                $question->getAnswersLabels()
            );

            $multiSelect = $showMultipleChoice === true ? $question->isMultipleChoice() : true;
            $choiceQuestion->setMultiselect($multiSelect);
            $choiceQuestion->setErrorMessage('Answer %s is invalid.');

            $answer = $questionHelper->ask($input, $output, $choiceQuestion);

            $answers = true === $multiSelect ? $answer : array($answer);
            $answer  = true === $multiSelect ? implode(', ', $answer) : $answer;

            $set->setAnswer($i, $answers);

            if($input->getOption("training"))
            {
                $uniqueSet = new Set(array($i => $question));

                $uniqueSet->setAnswer($i, $answers);

                $this->displayResults($uniqueSet, $output);
            }

            $output->writeln('<comment>✎ Your answer</comment>: ' . $answer . "\n");
        }
    }

    /**
     * Returns results
     *
     * @param Set             $set    A Certificationy questions Set instance
     * @param OutputInterface $output A Symfony Console output instance
     */
    protected function displayResults(Set $set, OutputInterface $output)
    {
        $results = array();

        $questionCount = 1;

        foreach($set->getQuestions() as $key => $question) {
            $isCorrect = $set->isCorrect($key);
            $label = wordwrap($question->getQuestion(), self::WORDWRAP_NUMBER, "\n");

            $results[] = array(
                sprintf('<comment>#%d</comment> %s', $questionCount++, $label),
                wordwrap(implode(', ', $question->getCorrectAnswersValues()), self::WORDWRAP_NUMBER, "\n"),
                $isCorrect ? '<info>✔</info>' : '<error>✗</error>'
            );
        }

        if ($results) {
            $tableHelper = $this->getHelper('table');
            $tableHelper
                ->setHeaders(array('Question', 'Correct answer', 'Result'))
                ->setRows($results)
            ;

            $tableHelper->render($output);

            $output->writeln(
                sprintf('<comment>Results</comment>: <error>errors: %s</error> - <info>correct: %s</info>', $set->getErrorsNumber(), $set->getValidNumber())
            );
        }
    }

    /**
     * Returns configuration file path
     *
     * @param null|string $config
     *
     * @return String $path      The configuration filepath
     */
    protected function path($config = null)
    {
        return $config ? $config : dirname(__DIR__).DIRECTORY_SEPARATOR.('config.yml');
    }
}

