<?php

declare(strict_types=1);

namespace Admin\News;

use Admin\Infrastructure\Component\Breadcrumb;
use Admin\Infrastructure\Component\Create\CreateComponent;
use Admin\Infrastructure\Component\Field\Autosearch;
use Admin\Infrastructure\Component\Field\Input;
use Admin\Infrastructure\Component\Header;
use Admin\Infrastructure\Router\Attributes\Route;
use App\Infrastructure\Doctrine\Flusher;
use App\News\Domain\News;
use App\News\Domain\NewsRepository;
use App\User\User\Domain\UserRepository;
use App\User\User\Query\FindUsersByPattern;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

/**
 * Компонент для создания новости
 */
#[Route('news/create')]
final class NewsCreateComponent extends CreateComponent
{
    public static string $updateEntityComponent = NewsUpdateComponent::class;

    public static string $indexEntityComponent = NewsIndexComponent::class;

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
                    title: 'Add News',
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
                rules: ['required', 'min:3'],
            ),
            Input::make(
                name: 'published_date',
                label: 'Published At',
                rules: ['required', 'date'],
                options: [
                    'type' => 'datetime-local',
                ],
            ),
            Autosearch::make(
                name: 'user',
                label: 'User',
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
     * @param array{
     *     title: non-empty-string,
     *     user: array{
     *         id: non-empty-string
     *     }
     * } $data
     */
    public function saveAndReturnEntityId(Flusher $flusher, array $data): string
    {
        $user = app(UserRepository::class)->find(
            new Uuid((string) $data['user']['id']),
        );

        if (!$user) {
            abort(Response::HTTP_NOT_FOUND, 'User not found');
        }

        $news = new News(
            title: $data['title'],
            user: $user,
        );

        app(NewsRepository::class)->add($news);

        $flusher->flush();

        /**
         * @var positive-int $lastId
         *
         * @todo временно, т.к. мы не знаем ид созданной новости
         */
        $lastId = app(EntityManager::class)
            ->getRepository(News::class)
            ->createQueryBuilder('n')
            ->select('n.id')
            ->orderBy('n.id', Order::Descending->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

        return (string) $lastId;
    }
}
