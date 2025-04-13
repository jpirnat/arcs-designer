<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\SetsModel;

final readonly class SetsController
{
    public function __construct(
        private SetsModel $model,
    ) {}

    public function setData(): void
    {
        $this->model->setData();
    }
}
