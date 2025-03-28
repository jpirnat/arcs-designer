<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\CardId;

final class CardModel
{
    private(set) array $iterations = [];
    private(set) array $card = [];
    private(set) array $affinities = [];
    private(set) array $speedModifiers = [];
    private(set) array $zoneModifiers = [];
    private(set) array $cardTypes = [];
    private(set) array $maxLengths = [];

    public function __construct(
        private readonly CardIterationRepositoryInterface $iterationRepository,
        private readonly AffinityRepositoryInterface $affinityRepository,
    ) {}

    public function setAddData(): void
    {
        $this->iterations = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

        $this->card = [
            'iterationId' => null,
            'name' => '',
            'affinityId' => null,
            'cost' => null,
            'enflowable' => null,
            'speedModifier' => null,
            'zoneModifier' => null,
            'startingLife' => null,
            'burden' => null,
            'cardType' => null,
            'rulesText' => '',
            'attack' => null,
            'defense' => null,
        ];

        $this->setCommonData();
    }

    public function setEditData(string $cardId): void
    {
        $this->iterations = [];
        $this->card = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

        $cardId = new CardId((int) $cardId);
        $iterations = $this->iterationRepository->getByCard($cardId);
        foreach ($iterations as $iteration) {
            $this->iterations[] = [
                'id' => $iteration->id->value,
                'createdAt' => $iteration->createdAt->format('M j, Y g:ia'),
            ];
        }

        $iteration = $this->iterationRepository->getCurrent($cardId);

        $this->card = [
            'iterationId' => $iteration->id->value,
            'name' => $iteration->name,
            'affinityId' => $iteration->affinityId?->value,
            'cost' => $iteration->cost,
            'enflowable' => $iteration->enflowable,
            'speedModifier' => $iteration->speedModifier?->value,
            'zoneModifier' => $iteration->zoneModifier?->value,
            'startingLife' => $iteration->startingLife,
            'burden' => $iteration->burden,
            'cardType' => $iteration->cardType?->value,
            'rulesText' => $iteration->rulesText,
            'attack' => $iteration->attack,
            'defense' => $iteration->defense,
        ];

        $this->setCommonData();
    }

    private function setCommonData(): void
    {
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [
            'name' => CardIteration::MAX_LENGTH_NAME,
            'rulesText' => CardIteration::MAX_LENGTH_RULES_TEXT,
        ];

        $affinities = $this->affinityRepository->getAll();
        foreach ($affinities as $affinity) {
            $this->affinities[] = [
                'id' => $affinity->id->value,
                'name' => $affinity->name,
            ];
        }

        $speedModifiers = SpeedModifier::getAll();
        foreach ($speedModifiers as $speedModifier) {
            $this->speedModifiers[] = [
                'value' => $speedModifier->value,
                'name' => $speedModifier->name,
            ];
        }

        $zoneModifiers = ZoneModifier::getAll();
        foreach ($zoneModifiers as $zoneModifier) {
            $this->zoneModifiers[] = [
                'value' => $zoneModifier->value,
                'name' => $zoneModifier->name,
            ];
        }

        $cardTypes = CardType::getAll();
        foreach ($cardTypes as $cardType) {
            $this->cardTypes[] = [
                'value' => $cardType->value,
                'name' => $cardType->name,
            ];
        }
    }
}
