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
  
    # запуск среды Tinker 
    make tinker
    ```
- запуск проверок качества кода
    ```shell 
    # Запустить все проверки  
    make check
  
    # Запустить все исправления  
    make fix
    ```

## Модули

Проект имеет модульную архитектуру, что позволяет каждый модуль реализовывать индивидуально.
Модули разделены согласно своему контексту и должны иметь слабые связи между собой (cohesion).

Логика CRUD-модулей ограничена простыми операциями с данными, что упрощает их реализацию.

* [Инструменты для работы с OpenAPI](backend/dev/OpenApi/README.md)
* [Подключение к БД Doctrine](backend/README.md)

## Copyright and license

Copyright © [Studio 15](http://15web.ru), 2012 - Present.   
Code released under [the MIT license](https://opensource.org/licenses/MIT).
