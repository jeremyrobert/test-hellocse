#---VARIABLES---------------------------------#
MAKEFLAGS += -s

#---DOCKER---#
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) down
#------------#

#---LARAVEL (PHP ARTISAN)-#
ARTISAN = php artisan
#------------#

#---COMPOSER-#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update
#------------#

#---TEST-#
TEST = $(ARTISAN) test
#------------#
#---------------------------------------------#

## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Laravel-And-Docker-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#

## === 🐋  DOCKER ================================================
docker-up: ## Start docker containers.
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-up

docker-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop
#---------------------------------------------#

## === 🎛️  LARAVEL (PHP ARTISAN) ===============================================
pa: ## List and Use All Laravel commands (make pa command="commande-name").
	$(ARTISAN) $(command)
.PHONY: pa

pa-cac: ## Clear laravel all cache.
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) event:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear
.PHONY: pa-cac

pa-cbc: ## Clear laravel bootstrap cache.
	$(ARTISAN) optimize:clear
.PHONY: pa-cbc

pa-db: ## Display information about the given database table.
	$(ARTISAN) db:table
.PHONY: pa-db

pa-dbs: ## Seed the database with records.
	$(ARTISAN) db:seed
.PHONY: pa-dbs

pa-dbd: ## Dump the given database schema.
	$(ARTISAN) schema:dump
.PHONY: pa-dbd

pa-m: ## Run the database migrations.
	$(ARTISAN) migrate
.PHONY: pa-m

pa-mf: ## Drop all tables and re-run all migrations.
	$(ARTISAN) migrate:fresh
.PHONY: pa-mf

pa-mfs: ## Drop all tables and re-run all migrations with seeders.
	$(ARTISAN) migrate:fresh --seed
.PHONY: pa-mfs

pa-rl: ## List all registered routes.
	$(ARTISAN) route:list --except-vendor
.PHONY: pa-rl
#---------------------------------------------#

## === 📦  COMPOSER ==============================================
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(COMPOSER) validate
.PHONY: composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(COMPOSER) validate --strict --check-lock
.PHONY: composer-validate-deep
#---------------------------------------------#

## === 🔎  TESTS =================================================
# If the first argument is "test"...
ifeq (test,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "run"
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(RUN_ARGS):;@:)
endif

test: ## Run tests.
	$(TEST) $(RUN_ARGS)
.PHONY: test

test-parallel: ## Run tests with parallel.
	$(TEST) --parallel
.PHONY: test-parallel

test-coverage: ## Run tests with coverage.
	XDEBUG_MODE=coverage $(TEST) --coverage --parallel
.PHONY: test-coverage
#---------------------------------------------#

## === ⭐  OTHERS =================================================
start: docker-up ## Start project.
.PHONY: start

stop: docker-stop ## Stop project.
.PHONY: stop

helpers: ## Generate helpers.
	$(ARTISAN) ide-helper:generate
	$(ARTISAN) ide-helper:models --write-mixin --nowrite
	$(ARTISAN) ide-helper:meta
.PHONY: helpers

lint: ## Lint code with pint.
	./vendor/bin/pint

analyse: ## PHP Static Analysis.
	./vendor/bin/phpstan analyse
.PHONY: analyse

documentation: ## Generate documentation.
	$(ARTISAN) l5-swagger:generate --all

before-commit: ## Run before commit.
	$(MAKE) -s helpers
	$(MAKE) -s lint
	$(MAKE) -s analyse
	$(MAKE) -s documentation
	$(MAKE) -s test
.PHONY: before-commit

reset-db: ## Reset database.
	$(eval CONFIRM := $(shell read -p "Are you sure you want to reset the database? [y/N] (default: N) " CONFIRM && echo $${CONFIRM:-N}))
	@if [ "$(CONFIRM)" = "y" ]; then \
		$(MAKE) -s pa-mf; \
		$(MAKE) -s pa-dbs; \
	fi
.PHONY: reset-db
#---------------------------------------------#
