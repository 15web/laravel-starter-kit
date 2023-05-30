# Тестовая пробная сборка для старта проекта на Laravel

## Управление проектом
- общие операции
    ```shell
    # установка и запуск
    make init
    
    # запуск приложения  
    make up
  
    # остановка приложения  
    make down
  
    # просмотр логов
    make logs {service}   
    make logs backend # например
    make logs -f backend # в реальном времени
  
    # установка git hooks 
    make hooks-install
    ```
- запуск проверок качества кода
    ```shell
    # PHPStan
    make stan

    # PHP CS Fixer
    make fixer-check
    make fixer-fix

    # PHP_CodeSniffer
    make sniffer-check
    make sniffer-fix
  
    # PHPUnit
    make test
  
    # Запустить все проверки  
    make check
  
    # Запустить все исправления  
    make fix
    ```
- запуск команды в контейнере бэкенда.
    ```shell
    # базовый скрипт с аргументом
    make run
    # по сути это шорткат для
    docker-compose run --rm backend-cli

    # по умолчанию в контейнере установлена команда php -a
    make run
      Environment is set up
      Creating laravel-start-local_backend-cli_run ... done
      Interactive shell
      php >

    # выполнить php скрипт можно переопределив команду
    make run php config/auth.php

    # также можно указывать любой исполняемый файл
    make run cat .gitignore
  
    # файл считается исполняемым, если у него есть shebang(например #!/usr/bin/env php)
    # и права на исполнение (chmod +x executable_shebanged_script.php)
    make run composer i
    make run vendor/bin/phpstan analyse -c phpstan.neon --ansi
    ```

## Copyright and license

Copyright © [Studio 15](http://15web.ru), 2012 - Present.   
Code released under [the MIT license](https://opensource.org/licenses/MIT).
