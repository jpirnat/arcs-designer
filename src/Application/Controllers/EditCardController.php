<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Controllers;

use Jp\ArcsDesigner\Application\Models\EditCardModel;
use Psr\Http\Message\ServerRequestInterface;

final readonly class EditCardController
{
    public function __construct(
        private EditCardModel $model,
    ) {}

    /** @noinspection DuplicatedCode */
    public function addCard(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

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
        $attack = (string) ($data['attack'] ?? '');
        $defense = (string) ($data['defense'] ?? '');

        $this->model->addCard(
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
            $attack,
            $defense,
        );
    }

    /** @noinspection DuplicatedCode */
    public function editCard(ServerRequestInterface $request): void
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

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
        $attack = (string) ($data['attack'] ?? '');
        $defense = (string) ($data['defense'] ?? '');

        $this->model->editCard(
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
            $attack,
            $defense,
        );
    }
}
