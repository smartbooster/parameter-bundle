##
## Docker commands
## ---------------
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
	docker exec -it --user=dev $(APPLICATION)-php bash
