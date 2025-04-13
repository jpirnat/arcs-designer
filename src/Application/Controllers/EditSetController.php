<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\EditSetModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class EditSetController
{
    public function __construct(
        private EditSetModel $model,
    ) {}

    /** @noinspection DuplicatedCode */
    public function addSet(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $name = (string) ($data['name'] ?? '');

        $this->model->addSet(
            $name,
        );
    }

    /** @noinspection DuplicatedCode */
    public function editSet(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $setId = (string) ($data['setId'] ?? '');
        $name = (string) ($data['name'] ?? '');

        $this->model->editSet(
            $setId,
            $name,
        );
    }
}
