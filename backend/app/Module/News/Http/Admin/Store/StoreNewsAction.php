<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Store;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveFromRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\News;
use App\Module\News\Domain\NewsRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания записи в новостях
 *
 * @todo test (201, exists, 400)
 */
final readonly class StoreNewsAction
{
    public function __construct(
        private NewsRepository $newsCollection,
        private Flusher $flusher,
        private ResolveFromRequest $resolveApiRequest,
        private ResolveResponse $resolveApiResponse,
    ) {}

    #[Router\Post('/news')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveApiRequest)(StoreNewsRequest::class);

        $isNewsExists = $this->newsCollection->isExistsByTitle(
            $request->title
        );

        if ($isNewsExists) {
            throw ApiException::createDomainException('Новость с таким заголовком уже существует', Error::NEWS_EXISTS);
        }

        $news = new News($request->title);
        $this->newsCollection->add($news);

        $this->flusher->flush();

        /** @var positive-int $id */
        $id = $news->getId();

        /** @var non-empty-string $title */
        $title = $news->getTitle();

        $response = new StoreNewsResponse(
            id: $id,
            title: $title,
            createdAt: $news->getCreatedAt(),
        );

        return ($this->resolveApiResponse)($response);
    }
}
