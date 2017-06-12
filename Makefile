DC=docker-compose
DID=$(DC) ps -q app
RUN=$(DC) run --rm app

.DEFAULT_GOAL := help
.PHONY: help start stop

help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

start:          ## Start the project
start: build up vendor go_bash

bash:           ## Go to the bash container of the application
bash: go_bash

stop:           ## Stop docker containers
	$(DC) kill

# Internal rules
build:
	$(DC) build

up:
	$(DC) up -d

go_bash:
	docker exec -i -t $(shell $(DID)) /bin/bash

vendor: composer.lock
	@$(RUN) composer install
