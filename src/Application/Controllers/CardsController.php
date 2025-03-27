<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\CardsModel;

final readonly class CardsController
{
    public function __construct(
        private CardsModel $model,
    ) {}

    public function setData(): void
    {
        $this->model->setData();
    }
}
