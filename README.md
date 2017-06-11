<p align="center">
    <img src="https://avatars0.githubusercontent.com/u/8029934?v=3&s=200"> Certificationy CLI
</p>

[![Build Status](https://secure.travis-ci.org/certificationy/certificationy-cli.png?branch=master)](http://travis-ci.org/certificationy/certificationy-cli)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cd3b6bc1-632e-491a-abfc-43edc390e1cc/mini.png)](https://insight.sensiolabs.com/projects/cd3b6bc1-632e-491a-abfc-43edc390e1cc)

This is the CLI tool to train on certifications.

# How it looks?

![Certificationy application](https://cloud.githubusercontent.com/assets/1247388/17698070/434e3944-63b9-11e6-80c6-91706dbbea50.png "Certificationy application")

# Installation and update

## Using Composer
```
$ composer create-project certificationy/certificationy-cli
$ php certificationy.php
```

##  With Docker and Docker compose

### Install the project prerequisites
The project has prerequisites:

- [Docker][docker] (1.12+)
- [Docker-composer][docker-compose] (1.10+)
- [GNU make][make]

To install Docker, refer to the official documentation for your operating system: https://docs.docker.com/engine/installation/.

Once Docker installed, to check its smooth running, run docker -v, you should get something like suit:

```
$ Docker -v
Docker version 1.12.4, build 1564f02
```
> You must use the minimum version 1.12 of Docker.

To install the docker-composer, please also refer to the official documentation: https://docs.docker.com/compose/install/.

Once docker-composes installed (install it globally to be able and access from anywhere), to check its proper functioning, run docker-compose -v, you should get something like suit:

```
$ Docker-compos -v
Docker-composer version 1.10.0, build 4bd6f1a
```

> You must use the docker-compose version 1.10 minimum.

A makefile allows you to manipulate the container simply and easily.
You have to be able to run `make -v`, which you are ready to choose:

```
$ Make -v
GNU Make 4.1
Built for x86_64-pc-linux-gnu
Copyright (C) 1988-2014 Free Software Foundation, Inc.
GPLv3 + license: GNU GPL version 3 or later <http://gnu.org/licenses/gpl.html>
This is free software: you are allowed to modify and redistribute.
There is NO WARRANTY, to the extent of the will of the law.
```

> ** Note **: If you are using Windows, we strongly recommend that you use the Linux console included in
> Windows 10 (https://msdn.microsoft.com/en-us/commandline/wsl/install_guide) or to use an emulator for
> Command to be able to use `make` which will greatly facilitate the work.

### Using the Container

You should then be able to run `make` which will show you using the Makefile:

```
$ make
```

Start the application with `make start`:

```
$make start
docker-compose build
Building app
Step 1/19 : FROM php:7.1-fpm-alpine
7.1-fpm-alpine: Pulling from library/php
... # pulling image
Successfully built 5f936eaf8e42
Successfully tagged certificationycli_app:latest
docker-compose up -d
Recreating certificationycli_app_1 ... 
Recreating certificationycli_app_1 ... done
docker exec -i -t a8fce5464ec7385e59a187165acf65040d9f31d5e979025987d3be3881bdf65c /bin/bash
root@a8fce5464ec7:/app#
```

Once the procedure is complete you can already use the bash of the container:

```
$ php certificationy.php # Run Certificationy CLI
```

To exit bash docker

```
$ exit 
```

Stop the application with `make stop`:

```
$ make stop 
docker-compose kill
Killing certificationycli_app_1 ... done
```

## More run options

### Select the number of questions
```
$ php certificationy.php start --number=10
```

The default value is 20.

### List categories
```
$ php certificationy.php start --list [-l]
```

Will list all the categories available

### Only questions from certain categories
```
$ php certificationy.php start "Automated tests" "Bundles"
```

Will only get the questions from the categories "Automated tests" and "Bundles"

Use the category list from [List categories](#list-categories)

### Hide the information that questions are/aren't multiple choice
```
$ php certificationy.php start --hide-multiple-choice
```

As default, the information will be displayed

![Multiple choice](https://cloud.githubusercontent.com/assets/795661/3308225/721b5324-f679-11e3-8d9d-62ba32cd8e32.png "Multiple choice")

### Set custom configuration file
```
$ bin/certificationy start --config=../config.yml
```

Will set custom config file

### And all combined
```
$ php certificationy.php start --number=5 --hide-multiple-choice "Automated tests" "Bundles"
```

* 5 questions
* We will hide the information that questions are/aren't multiple choice
* Only get questions from category "Automated tests" and "Bundles"

> Note: if you pass --list [-l] then you will ONLY get the category list, regarding your other settings
