<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Jp\ArcsDesigner\Application\Models\AddCommentModel;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class AddCommentView
{
    public function __construct(
        private AddCommentModel $model,
    ) {}

    public function getData(): ResponseInterface
    {
        if ($this->model->comment) {
            return $this->success();
        }

        return $this->failure();
    }

    private function success(): ResponseInterface
    {
        return new JsonResponse(['data' => [
            'comment' => $this->model->comment,
        ]]);
    }

    private function failure(): ResponseInterface
    {
        return new JsonResponse(['error' => [
            'message' => $this->model->errorMessage,
        ]]);
    }
}
