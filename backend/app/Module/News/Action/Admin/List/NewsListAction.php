<?php

declare(strict_types=1);

namespace App\Module\News\Action\Admin\List;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\News\Model\NewsCollection;
use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use App\Module\User\Authorization\ByRole\Role;
use Illuminate\Http\JsonResponse;
use Iterator;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра новостей
 */
#[Router\Middleware('auth')]
final readonly class NewsListAction
{
    public function __construct(
        private NewsCollection $newsCollection,
        private ResolveApiResponse $resolveApiResponse,
        private DenyUnlessUserHasRole $denyUnlessUserHasRole,
    ) {}

    #[Router\Get('/news/list')]
    public function __invoke(): JsonResponse
    {
        ($this->denyUnlessUserHasRole)(Role::User);

        return ($this->resolveApiResponse)($this->getNewsData());
    }

    /**
     * @return Iterator
     */
    private function getNewsData(): iterable
    {
        $newsAll = $this->newsCollection->findAll();
        foreach ($newsAll as $news) {
            yield new NewsListData($news->getId(), $news->getTitle(), $news->getCreatedAt());
        }
    }
}
