<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\CardModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class CardController
{
    public function __construct(
        private CardModel $model,
    ) {}

    public function setAddData(ServerRequestInterface $request): void
    {
        $setId = $request->getQueryParams()['set'] ?? '';

        $this->model->setAddData($setId);
    }

    public function setEditData(ServerRequestInterface $request): void
    {
        $cardId = $request->getAttribute('id');

        $this->model->setEditData($cardId);
    }
}
