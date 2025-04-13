<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\SetsModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class SetsView
{
    public function __construct(
        private SetsModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        return new JsonResponse(['data' => [
            'sets' => $this->model->sets,
        ]]);
    }
}
