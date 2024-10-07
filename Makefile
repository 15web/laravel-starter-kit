.PHONY: all

init: setup \
	build \
	up \
	migrate
	@echo "Done"

up:
	docker compose up --detach --force-recreate --remove-orphans

down:
	docker compose down --remove-orphans

build:
	docker compose build backend mysql
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist

update:
	docker compose run --rm backend-cli composer update

create-migration: # Создание миграций БД
	docker compose run --rm backend ./bin/doctrine migrations:diff

migration-prev: # Откатить последнюю миграцию
	docker compose run --rm backend ./bin/doctrine migrations:migrate prev

migrate:
	docker compose run --rm backend-cli php artisan migrate --force
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate --no-interaction

tinker:
	docker compose run --rm backend-cli php artisan tinker

check: # Проверка проекта
	make composer-validate
	make composer-audit
	make lint
	make test

composer-validate: # Валидация композера
	docker compose run --rm backend-cli composer validate --strict

composer-audit: # Проверка пакетов
	docker compose run --rm backend-cli composer audit --format=plain

fix: # Автоматическая правка кода
	make fixer-fix
	make rector-fix

phpstan: # Запустить phpstan
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c dev/PHPStan/phpstan-config.neon --memory-limit 2G --ansi

phpstan-update-baseline: # Обновить baseline для phpstan
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c dev/PHPStan/phpstan-config.neon --memory-limit 2G --generate-baseline

fixer-check: # Проверка стиля написания кода
	docker compose run --rm backend-cli vendor/bin/php-cs-fixer --config=dev/PHPCsFixer/php-cs-fixer-config.php fix --dry-run --diff --ansi -v

fixer-fix: # Фикс стиля написания кода
	docker compose run --rm backend-cli vendor/bin/php-cs-fixer --config=dev/PHPCsFixer/php-cs-fixer-config.php fix

rector-check: # Какой код необходимо отрефакторить
	docker compose run --rm backend-cli vendor/bin/rector process --config=dev/Rector/rector.config.php --dry-run --ansi

rector-fix: # Рефакторинг кода
	docker compose run --rm backend-cli vendor/bin/rector process --config=dev/Rector/rector.config.php --clear-cache

lint: # Проверка кода
	make fixer-check
	make rector-check
	make phpstan

test: # Запуск тестов
	docker compose run --rm backend-cli php artisan test

test-single: # Запуск одного теста, пример: make test-single class=TaskCommentBodyTest
	docker compose run --rm backend-cli php artisan test --filter=$(class)

logs:
	@docker compose logs $(Arguments)

hooks-install:
	printf '#!/usr/bin/env sh\n\n./manage.bash check;\n' > .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit

setup:
	@[ -x ./docker/bin/setup_envs ] || chmod +x ./docker/bin/setup_envs
	@./docker/bin/setup_envs
	@echo "Environment is set up"
