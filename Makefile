# HELPERS
COMPOSE_RUN = \
	docker-compose -f docker-compose.run.yml

COMPOSE_DEV = \
	docker-compose -f docker-compose.dev.yml

PHP_EXEC = \
	$(COMPOSE_RUN) exec php

# MAIN DOCKER COMMANDS
start:
	$(MAKE) build
	$(COMPOSE_RUN) up -d

start-dev:
	$(MAKE) build
	COMPOSE_MODE=dev $(COMPOSE_DEV) up -d

build:
	DOCKER_BUILDKIT=1 docker build -f docker/php-dev.Dockerfile -t ddd_example_php:latest ./
	DOCKER_BUILDKIT=1 docker build -f docker/nginx.Dockerfile -t ddd_example_nginx:latest ./

stop:
	$(COMPOSE_RUN) stop | true

php-sh:
	$(PHP_EXEC) /bin/bash
