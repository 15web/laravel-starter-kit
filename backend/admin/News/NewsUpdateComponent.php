<?php

declare(strict_types=1);

namespace Admin\News;

use Admin\Infrastructure\Component\Breadcrumb;
use Admin\Infrastructure\Component\Field\Autosearch;
use Admin\Infrastructure\Component\Field\Input;
use Admin\Infrastructure\Component\Header;
use Admin\Infrastructure\Component\Update\UpdateComponent;
use Admin\Infrastructure\Router\Attributes\Route;
use App\Infrastructure\Doctrine\Flusher;
use App\News\Domain\News;
use App\News\Domain\NewsRepository;
use App\User\User\Query\FindUsersByPattern;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Компонент для редактирования новости
 */
#[Route('news/{id}')]
final class NewsUpdateComponent extends UpdateComponent
{
    public static string $indexEntityComponent = NewsIndexComponent::class;

    public function resolveEntity(string $entityId): ?object
    {
        return app(NewsRepository::class)->find((int) $entityId);
    }

    public function header(): Header
    {
        return new Header(
            title: 'News title',
            subtitle: $this->entity['title'],
            breadcrumbs: [
                new Breadcrumb(
                    title: 'News',
                    url: '/news',
                ),
                new Breadcrumb(
                    title: \sprintf('Edit News - %s', $this->entity['title']),
                ),
            ],
        );
    }

    public function formFields(): array
    {
        return [
            Input::make(
                name: 'title',
                label: 'News Title',
                value: $this->entity['title'],
                rules: ['required', 'min:3'],
            ),
            Input::make(
                name: 'published_date',
                label: 'Published At',
                value: new DateTimeImmutable($this->entity['createdAt'])->format('Y-m-d\TH:i'),
                rules: ['required', 'date'],
                options: [
                    'type' => 'datetime-local',
                ],
            ),
            Autosearch::make(
                name: 'user',
                label: 'User',
                value: [
                    'id' => $this->entity['user']['id'],
                    'value' => $this->entity['user']['email'],
                ],
                rules: [
                    'id' => ['required'],
                ],
                options: [
                    'searchQuery' => FindUsersByPattern::class,
                ],
            ),
        ];
    }

    /**
     * @param non-empty-string $entityId
     * @param array{
     *     title: non-empty-string
     * } $data
     */
    public function updateAndReturnEntity(Flusher $flusher, string $entityId, array $data): News
    {
        $news = app(NewsRepository::class)->find((int) $entityId);

        if (!$news) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $news->setTitle($data['title']);

        $flusher->flush();

        return $news;
    }
}
