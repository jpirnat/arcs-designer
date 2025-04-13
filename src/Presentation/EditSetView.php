<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\EditSetModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class EditSetView
{
    public function __construct(
        private EditSetModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        $errorMessage = $this->model->errorMessage;

        $response = !$errorMessage
            ? ['data' => ['success' => true]]
            : ['error' => ['message' => $errorMessage]]
        ;

        return new JsonResponse($response);
    }
}
