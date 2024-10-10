<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Create;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Request\ResolveFromRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\News\Domain\News;
use App\Module\News\Domain\NewsRepository;
use App\Module\User\Authorization\ByRole\DenyUnlessUserHasRole;
use App\Module\User\Authorization\ByRole\Role;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания записи в новостях
 */
#[Router\Middleware('auth')]
final readonly class NewsCreateAction
{
    public function __construct(
        private NewsRepository $newsCollection,
        private Flusher $flusher,
        private ResolveFromRequest $resolveApiRequest,
        private ResolveResponse $resolveApiResponse,
        private DenyUnlessUserHasRole $denyUnlessUserHasRole,
    ) {}

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
