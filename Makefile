# HELPERS
COMPOSE = \
	docker-compose -f docker-compose.dev.yml

PHP_EXEC = \
	$(COMPOSE) exec php

# MAIN DOCKER COMMANDS
start:
	$(MAKE) build
	$(COMPOSE) up -d

build:
	DOCKER_BUILDKIT=1 docker build -f docker/php-dev.Dockerfile -t ddd_example_php:latest ./
	DOCKER_BUILDKIT=1 docker build -f docker/nginx.Dockerfile -t ddd_example_nginx:latest ./

stop:
	$(COMPOSE) stop | true

php-sh:
	$(PHP_EXEC) /bin/bash

# DEVELOPMENT COMMANDS
flush-database:
	$(PHP_EXEC) sh -c 'rm -rf /tmp/bowling-app-*'

check-code:
	$(PHP_EXEC) composer check

style-fix:
	$(PHP_EXEC) composer style-fix

# OTHER DOCKER COMMANDS
docker-ease:
	docker system prune --volumes --filter="label!=com.docker.compose.project" --force

docker-prune:
	docker system prune --force
	docker image prune --all --force
