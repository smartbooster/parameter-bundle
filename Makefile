# Variables
ENV?=dev
CONSOLE=php bin/console
APPLICATION := smartbooster-parameterbundle

include make/*.mk

##
## Installation and update
## -------
.PHONY: install
install: ## Install the project
ifeq ($(ENV),dev)
	composer install
else
	composer install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction
endif
