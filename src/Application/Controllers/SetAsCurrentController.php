<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\SetAsCurrentModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class SetAsCurrentController
{
    public function __construct(
        private SetAsCurrentModel $model,
    ) {}

    public function setData(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $cardId = (string) ($data['cardId'] ?? '');
        $iterationId = (string) ($data['iterationId'] ?? '');

        $this->model->setData(
            $cardId,
            $iterationId,
        );
    }
}
