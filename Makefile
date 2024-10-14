.PHONY: all

init: # Инициализация приложения
	make setup
	make build
	make up
	make migrate
	make test-install

up: # Запуск приложения
	docker compose up --detach --force-recreate --remove-orphans

down: # Остановка контейнеров
	docker compose down --remove-orphans

build: # Сборка приложения
	docker compose build
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist
	docker compose run --rm backend-cli php artisan key:generate --no-interaction

create-migration: # Создание миграций БД
	make doctrine-clear-cache;
	docker compose run --rm backend ./bin/doctrine migrations:diff

migration-prev: # Откатить последнюю миграцию
	make doctrine-clear-cache;
	docker compose run --rm backend ./bin/doctrine migrations:migrate prev

migrate: # Запуск миграций
	make doctrine-clear-cache;
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate --no-interaction

tinker: # Запуск консольного интерпретатора
	docker compose run --rm backend-cli php artisan tinker

clear: # Удаление кэша контейнера
	docker compose run --rm backend-cli php artisan clear-compiled

doctrine-clear-cache:
	rm -rf backend/storage/framework/cache/doctrine

check: # Проверка приложения
	make clear
	make composer-check
	make lint
	make test

composer-validate: # Валидация композера
	docker compose run --rm backend-cli composer validate --strict

composer-audit: # Проверка пакетов
	docker compose run --rm backend-cli composer audit --format=plain

composer-normalize: # Проверка синтаксиса composer.json
	docker compose run --rm backend-cli composer normalize --dry-run

composer-unused: # Проверка неиспользуемых зависимостей
	docker compose run --rm backend-cli vendor/bin/composer-unused --configuration=./dev/composer-unused.php

composer-require-check: # Проверка требуемых зависимостей, не указанных в composer.json
	docker compose run --rm backend-cli vendor/bin/composer-require-checker --config-file=./dev/composer-require-checker.json

composer-check: # Проверки композера
	make composer-validate
	make composer-normalize
	make composer-audit
	make composer-unused
	make composer-require-check

fix: # Автоматическая правка кода
	make fixer-fix
	make rector-fix

phpstan: # Запустить phpstan
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c dev/PHPStan/phpstan-config.neon --memory-limit 2G --ansi

phpstan-update-baseline: # Обновить baseline для phpstan
	docker compose run --rm backend-cli vendor/bin/phpstan analyse -c dev/PHPStan/phpstan-config.neon --memory-limit 2G --generate-baseline

psalm: # Запуск psalm
	docker compose run --rm backend-cli vendor/bin/psalm --config=./dev/psalm.xml

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
	make psalm

test-install: # Подготовка тестового окружения
	docker compose exec mysql mysql -proot -e "drop database if exists db_name_test;";
	docker compose exec mysql mysql -proot -e "create database if not exists db_name_test;";
	docker compose exec mysql mysql -proot -e "GRANT ALL PRIVILEGES ON db_name_test.* TO 'db_user'@'%';";
	docker compose run --rm backend-cli ./bin/doctrine --env=testing migrations:migrate --no-interaction

test: # Запуск тестов
	make doctrine-clear-cache;
	docker compose run --rm backend-cli php artisan test --env=testing

test-single: # Запуск одного теста, пример: make test-single class=TaskCommentBodyTest
	docker compose run --rm backend-cli php artisan test --env=testing --filter=$(class)

setup:
	@[ -x ./docker/bin/setup_envs ] || chmod +x ./docker/bin/setup_envs
	@./docker/bin/setup_envs
	@echo "Environment is set up"
