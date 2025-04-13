<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\SetModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class SetController
{
    public function __construct(
        private SetModel $model,
    ) {}

    public function setAddData(): void
    {
        $this->model->setAddData();
    }

    public function setEditData(ServerRequestInterface $request): void
    {
        $setId = $request->getAttribute('id');

        $this->model->setEditData($setId);
    }
}
