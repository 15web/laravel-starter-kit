<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category\Create;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Products\Model\Category;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class CategoryCreateAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Post('/category/create')]
    public function __invoke(): JsonResponse
    {
        $categoryCreateData = ($this->resolveApiRequest)(CategoryCreateRequest::class);

        $category = new Category();
        $category->title = $categoryCreateData->getTitle();
        $category->parent_id = $categoryCreateData->getParent();

        $category->save();

        $postCreateResponseData = new CategoryCreateResponse($category);

        return ($this->resolveApiResponse)($postCreateResponseData);
    }
}
