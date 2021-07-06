.DEFAULT_GOAL := help
.PHONY: help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' ./Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[34m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[34m##/[33m/'

##
## Docker
## -------
.PHONY: up
up: ## Start the project stack with docker
	docker-compose up

.PHONY: down
down: ## Kill the project stack with docker
	docker-compose down

.PHONY: ps
ps: ## List containers from project
	docker-compose ps

.PHONY: ssh
ssh: ## Access to the php container in interactive mode
	docker exec -it --user=dev smartbooster-parameterbundle-php bash

##
## Qualimetry
## -------

.PHONY: cs checkstyle
cs: checkstyle
checkstyle: ## PHP Checkstyle
	vendor/bin/phpcs --extensions=php -n --standard=PSR12 --report=full src tests

.PHONY: cb code-beautifier
cb: code-beautifier
code-beautifier: ## Code beautifier (Checkstyle fixer)
	vendor/bin/phpcbf --extensions=php --standard=PSR12 src tests

.PHONY: lint-php
lint-php: ## Linter PHP
	find src -type f -name "*.php" -exec php -l {} \;

.PHONY: composer-validate
composer-validate: ## Validate composer.json and composer.lock
	composer validate composer.json
	symfony check:security

.PHONY: cpd
cpd: ## Copy paste detector
	vendor/bin/phpcpd --fuzzy src

.PHONY: metrics
metrics: ## Build static analysis from the php in src. Repports available in ./phpmetrics/index.html
	vendor/bin/phpmetrics --report-html=phpmetrics src

.PHONY: phpstan
phpstan: ## Launch PHP Static Analysis
	vendor/bin/phpstan analyse src tests --level=6 -c phpstan.neon

.PHONY: qa qualimetry
qa: qualimetry ## Launch all qualimetry rules
qualimetry: checkstyle lint-php cpd composer-validate metrics phpstan

# ====================
## Testing
phpunit:
	vendor/bin/phpunit

coverage-text:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text

coverage-html:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html phpunit-coverage-html
