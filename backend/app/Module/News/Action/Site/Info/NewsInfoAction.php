<?php

declare(strict_types=1);

namespace App\Module\News\Action\Site\Info;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\News\Model\NewsCollection;
use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
#[Middleware('auth')]
final class NewsInfoAction
{
    public function __construct(
        private readonly NewsCollection $newsCollection,
        private readonly ResolveApiRequest $resolveApiRequest,
        private readonly ResolveApiResponse $resolveApiResponse,
        private readonly DenyUnlessUserHasRole $denyUnlessUserHasRole,
    ) {
    }

    #[Get('/news/info')]
    public function __invoke(): JsonResponse
    {
        $newsInfoRequest = ($this->resolveApiRequest)(NewsInfoRequest::class);

        $news = $this->newsCollection->findByTitle($newsInfoRequest->getTitle());
        if ($news === null) {
            throw ApiException::createNotFoundException('Новость не найдена.', Error::NEWS_NOT_FOUND);
        }

        $newsInfoResponse = new NewsInfoResponse($news);

        return ($this->resolveApiResponse)($newsInfoResponse);
    }
}
