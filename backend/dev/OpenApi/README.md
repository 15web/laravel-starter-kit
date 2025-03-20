# Инструменты для работы с OpenAPI

## Сборка openapi.yaml

Для удобства работы спецификация OpenAPI разбита на отдельные файлы и собирается с помощью команды.

Структура файлов:
* `backend/dev/OpenApi/resources/base.yaml` - базовые файлы с мета-информацией. 
  Директивы верхнего уровня `info`, `servers` и `security` нужно прописывать в них.
* `backend/dev/OpenApi/resources/%module_name%.yaml` - файлы с конфигурацией модулей. 
  Обязательно должны содержать директивы верхнего уровня `openapi`, `info`, `tags`, `paths` и `components`.
* `backend/dev/OpenApi/resources/common.yaml` - файл с общими для всех модулей компонентами.
* `backend/dev/OpenApi/resources/_template_module.yaml` - файл-шаблон для новых модулей, 
  содержит минимально необходимые директивы.

Для сборки следует запустить команду:
```shell
make generate-openapi
```
