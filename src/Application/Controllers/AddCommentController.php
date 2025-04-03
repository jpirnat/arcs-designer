<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\AddCommentModel;
use Jp\ArcsDesigner\Application\Models\BaseModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class AddCommentController
{
    public function __construct(
        private BaseModel $baseModel,
        private AddCommentModel $model,
    ) {}

    public function setData(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $userId = $this->baseModel->userId;
        $iterationId = (string) ($data['iterationId'] ?? '');
        $commentText = (string) ($data['commentText'] ?? '');

        $this->model->setData(
            $userId,
            $iterationId,
            $commentText,
        );
    }
}
