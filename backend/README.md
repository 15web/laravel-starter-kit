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
