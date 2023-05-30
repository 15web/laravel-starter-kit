# Тестовая пробная сборка для старта проекта на Laravel

[![Code quality status](https://github.com/15web/laravel-starter-kit/actions/workflows/check-code-quality.yml/badge.svg?branch=main)](https://github.com/15web/laravel-starter-kit/actions)

## Управление проектом
- общие операции
    ```shell
    # установка и запуск
    make init
    
    # запуск приложения  
    make up
  
    # остановка приложения  
    make down
  
    # запуск миграций 
    make migrate
  
    # просмотр логов
    make logs {service}   
    make logs backend # например
    make logs -f backend # в реальном времени
  
    # запуск среды Tinker 
    make tinker
  
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
## Copyright and license

Copyright © [Studio 15](http://15web.ru), 2012 - Present.   
Code released under [the MIT license](https://opensource.org/licenses/MIT).
