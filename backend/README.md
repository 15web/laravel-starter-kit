## Подключение к БД Doctrine

Doctrine настроен через атрибуты

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
/** @final */
class News
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
```

Для вызова команд миграции нужно запустить

``docker-compose run --rm php-cli ./backend/bin/doctrine``

Добавочные аргументы:

```
migrations:diff                   [diff] Generate a migration by comparing your current database to your mapping information.
migrations:dump-schema            [dump-schema] Dump the schema for your database to a migration.
migrations:execute                [execute] Execute one or more migration versions up or down manually.
migrations:generate               [generate] Generate a blank migration class.
migrations:latest                 [latest] Outputs the latest version
migrations:list                   [list-migrations] Display a list of all available migrations and their status.
migrations:migrate                [migrate] Execute a migration to a specified version or the latest available version.
migrations:rollup                 [rollup] Rollup migrations by deleting all tracked versions and insert the one version that exists.
migrations:status                 [status] View the status of a set of migrations.
migrations:sync-metadata-storage  [sync-metadata-storage] Ensures that the metadata storage is at the latest version.
migrations:version                [version] Manually add and delete migration versions from the version table.
```

Модели к БД Eloquent

Миграции хранятся в `backend/database/migrations` генерируются командой:

```
docker-compose run --rm backend-cli ./artisan make:migration {name}
```

Создается пустая миграция `2022_03_30_092154_post_table.php`

Миграция пишется путем вызова 

```phpt
Schema::create('posts', static function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('content');
            $table->timestamps();
        });
```

Применение миграции 

```docker-compose run --rm backend-cli ./artisan migrate```

## Генерация кода

Кастомные команды располагаются тут: `Infrastructure/Console/Commands`

1. Команда генерации коллекции CRUD экшенов: 
```
./manage.bash rb php artisan make:crud {name}
# например 
./manage.bash rb php artisan make:crud Photo
```
Корневой неймспейc для созданных ручек `backend/app/Module/{name}`

Пример кода созданной ручки:
```phpt
<?php

declare(strict_types=1);

namespace App\Module\Photo\Action\Create;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
final class PhotoCreateAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Get('/photo/create')]
    public function __invoke(): JsonResponse
    {
      // todo: implement action
    }
}
```
2. Команда для генерации единичного инвокейбл экшена: 
```
./manage.bash rb php artisan make:action '{name}' {path} {method}
# например 
./manage.bash rb php artisan make:action 'App\Module\Photo\Action\PhotoViewListAction' /photo/view-list Get
```
- Порядок аргументов имеет значение.
- Кавычки для имени класса обязательны.
