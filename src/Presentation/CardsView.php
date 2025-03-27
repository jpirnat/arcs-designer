<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\CardsModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class CardsView
{
    public function __construct(
        private CardsModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        return new JsonResponse(['data' => [
            'cards' => $this->model->cards,
        ]]);
    }
}
