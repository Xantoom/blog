# Executables (local)
DOCKER      = docker
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP          = $(PHP_CONT) php
PHP_BIN      = $(PHP_CONT) bin/console
DOCTRINE	 = $(PHP_CONT) php ./vendor/bin/doctrine-migrations
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

start: build up ## Build and start the docker hub

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

restart: down up ## Restart the docker hub

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

prune-all: ## Prune all docker resources
	@$(DOCKER) system prune --all --force --volumes

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req PHP_BIN/orm-pack'
	@$(eval c ?=)
	$(COMPOSER) $(c)

## —— Project 🐝 ———————————————————————————————————————————————————————————————
doctrine: ## Run doctrine commands, pass the parameter "c=" to run a given command, example: make doctrine c='migrate'
	@$(DOCTRINE) $(c)
