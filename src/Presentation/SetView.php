<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\SetModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class SetView
{
    public function __construct(
        private SetModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        return new JsonResponse(['data' => [
            'set' => $this->model->set,
            'cards' => $this->model->cards,
            'maxLengths' => $this->model->maxLengths,
        ]]);
    }
}
