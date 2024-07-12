# Executables (local)
DOCKER      = docker
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP          = $(PHP_CONT) php
PHP_BIN      = $(PHP_CONT) bin/console
COMPOSER     = $(PHP_CONT) composer

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The PHP_BIN-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

init: build start ## Build and start the containers

restart: down up ## Restart the docker hub

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

prune-all: ## Prune all docker resources
	@$(DOCKER) system prune --all --force --volumes

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req PHP_BIN/orm-pack'
	@$(eval c ?=)
	$(COMPOSER) $(c)

## —— Project 🐝 ———————————————————————————————————————————————————————————————
start: up require load-db load-fixtures  ## Start docker, load migrations and load fixtures

reload: load-db load-fixtures ## Load migrations and load fixtures

reset-db: drop-db reload ## Drop the database, load migrations and load fixtures

stop: down ## Stop docker

require: vendor ## Install requirements (composer)

load-db:
	@$(PHP_BIN) doctrine:database:create --if-not-exists
	@$(PHP_BIN) doctrine:migrations:migrate -n

load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	@$(PHP_BIN) doctrine:fixtures:load --no-interaction

migration: ## Generate a new migration
	@$(PHP_BIN) make:migration

migration-diff: ## Generate a new migration
	@$(PHP_BIN) doctrine:migrations:diff

migrate: ## Run migrations
	@$(PHP_BIN) doctrine:migrations:migrate --no-interaction

drop-db: ## Reset the database
	@$(PHP_BIN) doctrine:database:drop --force

vendor: ## Install vendors
vendor: c=install
vendor: composer
