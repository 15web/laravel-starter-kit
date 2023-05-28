.PHONY: all

C_GREEN='\033[0;32m'
C_RED='\033[0;31m'
C_BLUE='\033[0;34m'
C_END='\033[0m'

init: setup
	docker compose build backend mysql
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist
	docker compose up --detach --force-recreate --remove-orphans backend nginx mysql mailhog
	docker compose run --rm backend-cli php artisan migrate --force
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate --no-interaction
	docker compose up --detach --force-recreate queue
	@echo -e ${C_GREEN}Done${C_END}

up: setup
	docker compose up -d --force-recreate --remove-orphans

down:
	docker compose down --remove-orphans

update: setup
	docker compose pull
	docker compose build --pull
	docker compose run --rm backend-cli composer update

build: setup
	docker compose build
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist
	docker compose up -d --force-recreate --remove-orphans

run:
	@docker compose run --rm backend-cli $(Arguments)

stan:
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c phpstan.neon --ansi --memory-limit=256M

fixer-check:
	docker-compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --dry-run --diff --ansi -v

fixer-fix:
	docker-compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --ansi -v

sniffer-check:
	docker-compose run --rm backend-cli ./vendor/bin/phpcs -p

sniffer-fix:
	docker-compose run --rm backend-cli ./vendor/bin/phpcbf -p

test:
	docker-compose run --rm backend-cli ./vendor/bin/phpunit --colors=always --testsuite Unit

check:
	# todo: параллельный запуск
	docker-compose run --rm backend-cli vendor/bin/phpstan analyse -c phpstan.neon --ansi
	docker-compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --dry-run --diff --ansi -v
	docker-compose run --rm backend-cli ./vendor/bin/phpcs -p
	docker-compose run --rm backend-cli ./vendor/bin/phpunit --colors=always --testsuite Unit
	@echo -e ${C_GREEN}Checking is successfully completed${C_END}

fix:
	docker-compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --ansi -v
	docker-compose run --rm backend-cli ./vendor/bin/phpcbf -p

logs:
	@docker compose logs $(Arguments)

hooks-install:
	printf '#!/usr/bin/env sh\n\n./manage.bash check;\n' > .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit

setup:
	@[ -x ./docker/bin/setup_envs ] || chmod +x ./docker/bin/setup_envs
	@./docker/bin/setup_envs
	@echo -e ${C_GREEN}Environment is set up${C_END}
