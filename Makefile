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
