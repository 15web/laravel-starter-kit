<?php

declare(strict_types=1);

namespace App\Module\News\Action\Admin\Create;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\News\Model\News;
use App\Module\News\Model\NewsCollection;
use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use App\Module\User\Authorization\ByRole\Role;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
#[Router\Middleware('auth')]
final class NewsCreateAction
{
    public function __construct(
        private readonly NewsCollection $newsCollection,
        private readonly Flusher $flusher,
        private readonly ResolveApiRequest $resolveApiRequest,
        private readonly ResolveApiResponse $resolveApiResponse,
        private readonly DenyUnlessUserHasRole $denyUnlessUserHasRole,
    ) {
    }

    #[Router\Post('/news/create')]
    public function __invoke(): JsonResponse
    {
        ($this->denyUnlessUserHasRole)(Role::Admin);

        $newsCreateData = ($this->resolveApiRequest)(NewsCreateRequest::class);

        $isNewsExists = $this->newsCollection->isExistsByTitle($newsCreateData->getTitle());
        if ($isNewsExists) {
            /** @var string $message */
            $message = __('news::handler.exists');

            throw ApiException::createDomainException($message, Error::NEWS_EXISTS);
        }

        $news = new News($newsCreateData->getTitle());
        $this->newsCollection->add($news);

        $this->flusher->flush();

        $newsCreateResponseData = new NewsCreateResponse($news);

        return ($this->resolveApiResponse)($newsCreateResponseData);
    }
}
