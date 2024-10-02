.PHONY: all

C_GREEN='\033[0;32m'
C_RED='\033[0;31m'
C_BLUE='\033[0;34m'
C_END='\033[0m'

init: setup \
	build \
	up \
	migrate
	@echo -e ${C_GREEN}Done${C_END}

up:
	docker compose up --detach --force-recreate --remove-orphans

down:
	docker compose down --remove-orphans

build:
	docker compose build backend mysql
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist

update:
	docker compose run --rm backend-cli composer update

migrate:
	docker compose run --rm backend-cli php artisan migrate --force
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate --no-interaction

tinker:
	docker compose run --rm backend-cli php artisan tinker

stan:
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c phpstan.neon --ansi --memory-limit=256M

fixer-check:
	docker compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --dry-run --diff --ansi -v

fixer-fix:
	docker compose run --rm backend-cli vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --ansi -v

sniffer-check:
	docker compose run --rm backend-cli ./vendor/bin/phpcs -p

sniffer-fix:
	docker compose run --rm backend-cli ./vendor/bin/phpcbf -p

test:
	docker compose run --rm backend-cli ./vendor/bin/phpunit --colors=always --testsuite Unit

check: stan \
	fixer-check \
	sniffer-check \
	test
	@echo -e ${C_GREEN}Checking is successfully completed${C_END}

fix: fixer-fix \
	sniffer-fix
	@echo -e ${C_GREEN}Fix is successfully completed${C_END}

logs:
	@docker compose logs $(Arguments)

hooks-install:
	printf '#!/usr/bin/env sh\n\n./manage.bash check;\n' > .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit

setup:
	@[ -x ./docker/bin/setup_envs ] || chmod +x ./docker/bin/setup_envs
	@./docker/bin/setup_envs
	@echo -e ${C_GREEN}Environment is set up${C_END}
