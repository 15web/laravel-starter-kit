.PHONY: all

init: # Инициализация приложения
	make setup
	make build
	make up
	make migrate
	make test-install

up: # Запуск приложения
	docker compose up --detach --force-recreate --remove-orphans
	make clear-cache

down: # Остановка контейнеров
	docker compose down --remove-orphans

build: # Сборка приложения
	docker compose build
	docker compose run --rm backend-cli composer install --no-scripts --prefer-dist
	docker compose run --rm backend-cli php artisan key:generate --no-interaction

create-migration: # Создание миграций БД
	make clear-cache
	docker compose run --rm backend-cli ./bin/doctrine migrations:diff

migration-prev: # Откатить последнюю миграцию
	make clear-cache
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate prev

migrate: # Запуск миграций
	make clear-cache
	docker compose run --rm backend-cli ./bin/doctrine migrations:migrate --no-interaction

tinker: # Запуск консольного интерпретатора
	docker compose run --rm backend-cli php artisan tinker

clear-cache: # Удаление кэша
	docker compose run --rm backend-cli php artisan clear-compiled
	docker compose run --rm backend-cli php artisan cache:clear
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:query
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:metadata --flush
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:result --flush

clear-test-cache: # Удаление кэша для тестов
	docker compose run --rm backend-cli php artisan cache:clear --env=testing
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:query --env=testing
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:metadata --flush --env=testing
	docker compose run --rm backend-cli ./bin/doctrine orm:clear-cache:result --flush --env=testing

check: # Проверка приложения
	make clear-cache
	make composer-check
	make lint
	make check-openapi-schema
	make check-openapi-diff
	make test

composer-validate: # Валидация композера
	docker compose run --rm backend-cli composer validate --strict

composer-audit: # Проверка пакетов
	docker compose run --rm backend-cli composer audit --format=plain

composer-normalize: # Проверка синтаксиса composer.json
	docker compose run --rm backend-cli composer normalize --dry-run

composer-check: # Проверки композера
	make composer-validate
	make composer-normalize
	make composer-audit

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
	docker compose run --rm backend-cli bash -c 'PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --config=dev/PHPCsFixer/php-cs-fixer-config.php fix --dry-run --diff --ansi -v'

fixer-fix: # Фикс стиля написания кода
	docker compose run --rm backend-cli bash -c 'PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --config=dev/PHPCsFixer/php-cs-fixer-config.php fix'

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
	@for i in 1 2 3 4 ; do \
		docker compose exec pgsql dropdb -f --if-exists db_name_test_$$i; \
		docker compose exec pgsql createdb -O postgres db_name_test_$$i; \
		docker compose run --rm backend bash -c "TEST_TOKEN=$$i ./bin/doctrine --env=testing migrations:migrate --no-interaction"; \
	done

test: # Запуск тестов
	make clear-test-cache
	docker compose run --rm backend-cli bash -c 'vendor/bin/paratest -c ./dev/phpunit.xml --processes=4'

test-single: # Запуск одного теста, пример: make test-single class=TaskCommentBodyTest
	docker compose run --rm backend-cli bash -c 'vendor/bin/paratest -c ./dev/phpunit.xml --processes=4 --filter=$(class)'

spectral: # Валидация openapi.yaml с помощью spectral
	docker run --rm -v ${PWD}/backend:/app stoplight/spectral:latest lint /app/dev/openapi.yaml -F warn --ruleset=/app/dev/.spectral.yaml

check-openapi-schema: spectral # Валидация openapi.yaml

check-openapi-diff: # Валидация соответствия роутов и схемы openapi
	docker compose run --rm backend-cli php artisan openapi-routes-diff

setup:
	@[ -x ./docker/bin/setup_envs ] || chmod +x ./docker/bin/setup_envs
	@./docker/bin/setup_envs
	@echo "Environment is set up"
