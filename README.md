# Тестовая пробная сборка для старта проекта на laravel

## Управление проектом
- общие операции
    ```shell
    # установка и запуск
    ./manage.bash install # или i
  
    # просмотр логов
    ./manage.bash logs # или l
  
    # просмотр логов одного сервиса
    ./manage.bash logs backend

    # просмотр логов одного сервиса в реальном времени
    ./manage.bash l -f backend
    ./manage.bash logs --follow backend
  
    # установка git hooks 
    ./manage.bash hooks-install # или hi,
    ```
- запуск проверок качества кода
    ```shell
    # PHPStan
    ./manage.bash stan

    # PHP CS Fixer
    ./manage.bash fixer-check # или fc
    ./manage.bash fixer-fix # или ff

    # PHP_CodeSniffer
    ./manage.bash sniffer-check # или sc
    ./manage.bash sniffer-fix # или sf
  
    # PHPUnit
    ./manage.bash test-unit # или tu
  
    # Запустить все проверки  
    ./manage.bash check # или c
  
    # Запустить все исправления  
    ./manage.bash fix # или f
    ```
- запуск команды в контейнере бэкенда.
    ```shell
    # базовый скрипт с аргументом
    ./manage.bash rb
    # или то же самое 
    ./manage.bash run-backend
    # по сути это шорткат для
    docker-compose run --rm backend-cli

    # по умолчанию в контейнере установлена команда php -a
    ./manage.bash rb
      Envs set up!
      Creating laravel-start-local_backend-cli_run ... done
      Interactive shell
      php >

    # выполнить php скрипт можно переопределив команду
    ./manage.bash rb php config/auth.php

    # также можно указывать любой исполняемый файл
    ./manage.bash rb cat .gitignore
  
    # файл считается исполняемым, если у него есть shebang(например #!/usr/bin/env php)
    # и права на исполнение (chmod +x executable_shebanged_script.php)
    ./manage.bash rb composer i
    ./manage.bash rb vendor/bin/phpstan analyse -c phpstan.neon --ansi
    ```

## Copyright and license

Copyright © [Studio 15](http://15web.ru), 2012 - Present.   
Code released under [the MIT license](https://opensource.org/licenses/MIT).