<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\BaseModel;
use Jp\ArcsDesigner\Application\Models\EditCardModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class EditCardController
{
    public function __construct(
        private BaseModel $baseModel,
        private EditCardModel $model,
    ) {}

    /** @noinspection DuplicatedCode */
    public function addCard(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $userId = $this->baseModel->userId;
        $name = (string) ($data['name'] ?? '');
        $affinityId = (string) ($data['affinityId'] ?? '');
        $cost = (string) ($data['cost'] ?? '');
        $enflowable = (string) ($data['enflowable'] ?? '');
        $speedModifier = (string) ($data['speedModifier'] ?? '');
        $zoneModifier = (string) ($data['zoneModifier'] ?? '');
        $startingLife = (string) ($data['startingLife'] ?? '');
        $burden = (string) ($data['burden'] ?? '');
        $cardType = (string) ($data['cardType'] ?? '');
        $rulesText = (string) ($data['rulesText'] ?? '');
        $power = (string) ($data['power'] ?? '');
        $health = (string) ($data['health'] ?? '');
        $commentText = (string) ($data['commentText'] ?? '');

        $this->model->addCard(
            $userId,
            $name,
            $affinityId,
            $cost,
            $enflowable,
            $speedModifier,
            $zoneModifier,
            $startingLife,
            $burden,
            $cardType,
            $rulesText,
            $power,
            $health,
            $commentText,
        );
    }

    /** @noinspection DuplicatedCode */
    public function editCard(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $userId = $this->baseModel->userId;
        $iterationId = (string) ($data['iterationId'] ?? '');
        $name = (string) ($data['name'] ?? '');
        $affinityId = (string) ($data['affinityId'] ?? '');
        $cost = (string) ($data['cost'] ?? '');
        $enflowable = match ($data['enflowable']) {
            true, '1' => '1',
            false, '0' => '0',
            default => '',
        };
        $speedModifier = (string) ($data['speedModifier'] ?? '');
        $zoneModifier = (string) ($data['zoneModifier'] ?? '');
        $startingLife = (string) ($data['startingLife'] ?? '');
        $burden = (string) ($data['burden'] ?? '');
        $cardType = (string) ($data['cardType'] ?? '');
        $rulesText = (string) ($data['rulesText'] ?? '');
        $power = (string) ($data['power'] ?? '');
        $health = (string) ($data['health'] ?? '');
        $commentText = (string) ($data['commentText'] ?? '');

        $this->model->editCard(
            $userId,
            $iterationId,
            $name,
            $affinityId,
            $cost,
            $enflowable,
            $speedModifier,
            $zoneModifier,
            $startingLife,
            $burden,
            $cardType,
            $rulesText,
            $power,
            $health,
            $commentText,
        );
    }
}
