# Executables (local)
DOCKER      = docker
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP          			= $(PHP_CONT) php
DOCTRINE     			= $(PHP_CONT) php ./bin/doctrine.php
DOCTRINE-MIGRATIONS	 	= $(PHP_CONT) php ./vendor/bin/doctrine-migrations
COMPOSER     			= $(PHP_CONT) composer

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down restart logs composer vendor doctrine doctrine-migrations create-migration migrate load-fixtures drop-db load-db reset-db reset-db-without-fixtures php

## —— 🎵 🐳 The PHP_BIN-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up vendor load-db load-fixtures ## Build and start the docker hub

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

restart: down up ## Restart the docker hub

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req PHP_BIN/orm-pack'
	@$(eval c ?=)
	$(COMPOSER) $(c)

vendor: ## Install composer dependencies
	@$(COMPOSER) install

## —— Project 🐝 ———————————————————————————————————————————————————————————————
doctrine: ## Run doctrine commands, pass the parameter "c=" to run a given command, example: make doctrine c='migrate'
	@$(DOCTRINE) $(c)

doctrine-migrations: ## Run doctrine-migrations commands, pass the parameter "c=" to run a given command, example: make doctrine-migrations c='diff'
	@$(DOCTRINE-MIGRATIONS) $(c)

create-migration: ## Create a new migration
	@$(DOCTRINE-MIGRATIONS) diff

migrate: ## Migrate the database
	@$(DOCTRINE-MIGRATIONS) migrate --no-interaction

load-fixtures: ## Load fixtures
	@$(PHP) load-fixtures.php

drop-db: ## Drop the database
	@$(DOCTRINE) orm:schema-tool:drop --force

load-db: ## Load the database
	@$(DOCTRINE) orm:schema-tool:create
	@$(DOCTRINE-MIGRATIONS) migrate --no-interaction

reset-db: drop-db load-db load-fixtures ## Reset the database

reset-db-without-fixtures: drop-db load-db ## Reset the database

## —— PHP 🐘 ————————————————————————————————————————————————————————————————
php: ## Run PHP commands, pass the parameter "c=" to run a given command, example: make php c='bin/console'
	@$(PHP) $(c)
