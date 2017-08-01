#!/usr/bin/env php
<?php


/*
 * This file is part of the Certificationy CLI application.
 *
 * (c) Vincent Composieux <vincent.composieux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/vendor/autoload.php';

use Certificationy\Cli\Command\StartCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;

const VERSION = 2.0;
const APPLICATION_NAME = 'Certificationy';

$application = new Application(APPLICATION_NAME, VERSION);

$config = Yaml::parse(file_get_contents('config.yml'));

$startCommand = new StartCommand();
$application->add($startCommand);
$application->setDefaultCommand($startCommand->getName());

$application->run();
