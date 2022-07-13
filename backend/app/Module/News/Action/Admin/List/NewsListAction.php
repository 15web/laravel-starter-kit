<?php

declare(strict_types=1);

namespace App\Module\News\Action\Admin\List;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\News\Model\NewsCollection;
use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use App\Module\User\Authorization\ByRole\Role;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
#[Middleware('auth')]
final class NewsListAction
{
    public function __construct(
        private readonly NewsCollection $newsCollection,
        private readonly ResolveApiResponse $resolveApiResponse,
        private readonly Gate $gate,
        private readonly DenyUnlessUserHasRole $denyUnlessUserHasRole,
    ) {
    }

    #[Get('/news/list')]
    public function __invoke(): JsonResponse
    {
        ($this->denyUnlessUserHasRole)(Role::Admin);

        return ($this->resolveApiResponse)($this->getNewsData());
    }

    /**
     * @return \Iterator
     */
    private function getNewsData(): iterable
    {
        $newsAll = $this->newsCollection->findAll();
        foreach ($newsAll as $news) {
            yield new NewsListData($news->getId(), $news->getTitle(), $news->getCreatedAt());
        }
    }
}
