<?php

declare(strict_types=1);

namespace Admin\News;

use Admin\Infrastructure\Component\Breadcrumb;
use Admin\Infrastructure\Component\Contract\InteractsWithDeleteEntity;
use Admin\Infrastructure\Component\Contract\WithPagination;
use Admin\Infrastructure\Component\Header;
use Admin\Infrastructure\Component\Index\IndexComponent;
use Admin\Infrastructure\Component\Index\Property\Column;
use Admin\Infrastructure\Router\Attributes\Route;
use App\Infrastructure\ApiException\ApiException;
use App\News\Domain\NewsRepository;
use DateTimeImmutable;

/**
 * Управление списком новостей
 */
#[Route('news')]
final class NewsIndexComponent extends IndexComponent implements InteractsWithDeleteEntity
{
    use WithPagination;

    public static ?string $createEntityComponent = NewsCreateComponent::class;

    public static ?string $updateEntityComponent = NewsUpdateComponent::class;

    public function getEntityList(): array
    {
        return app(NewsRepository::class)->findAll(
            offset: $this->offset,
            limit: $this->limit,
        );
    }

    public function getEntityTotal(): int
    {
        return app(NewsRepository::class)->countTotal();
    }

    public function tableColumns(): array
    {
        return [
            new Column(
                name: 'id',
                sortable: true,
            ),
            new Column(
                name: 'createdAt',
                label: 'Date',
                sortable: true,
                formatted: static fn (string $createdAt): string => new DateTimeImmutable($createdAt)
                    ->format('j M Y H:i'),
            ),
            new Column(
                name: 'title',
            ),
            new Column(
                name: 'user.email',
                label: 'User Email',
            ),
        ];
    }

    public function header(): Header
    {
        return new Header(
            title: 'News title',
            subtitle: 'News subtitle',
            breadcrumbs: [
                new Breadcrumb(
                    title: 'News',
                    url: '/news',
                ),
                new Breadcrumb(
                    title: 'News List',
                ),
            ],
        );
    }

    public static function deleteEntity(string $entityId): void
    {
        $repository = app(NewsRepository::class);

        $news = $repository->find((int) $entityId);
        if (!$news) {
            throw ApiException::createNotFoundException(
                \sprintf('News #%s not found', $entityId),
            );
        }

        $repository->remove($news);
    }
}
