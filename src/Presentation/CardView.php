<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\CardModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class CardView
{
    public function __construct(
        private CardModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        return new JsonResponse(['data' => [
            'card' => $this->model->card,
            'affinities' => $this->model->affinities,
            'speedModifiers' => $this->model->speedModifiers,
            'zoneModifiers' => $this->model->zoneModifiers,
            'cardTypes' => $this->model->cardTypes,
            'maxLengths' => $this->model->maxLengths,
        ]]);
    }
}
