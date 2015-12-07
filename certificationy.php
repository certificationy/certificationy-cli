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
use KevinGH\Amend\Command;
use KevinGH\Amend\Helper;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;

$application = new Application('certificationy-cli', '1.1.1');

$config = Yaml::parse(file_get_contents('config.yml'));
$updateCommand = new Command('self-update');
$updateCommand->setManifestUri($config['manifest_uri']);
$application->add($updateCommand);
$application->getHelperSet()->set(new Helper());

$startCommand = new StartCommand();
$application->add($startCommand);
$application->setDefaultCommand($startCommand->getName());

$application->run();
