<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;

final class CardModel
{
    private(set) array $card = [];
    private(set) array $affinities = [];
    private(set) array $speedModifiers = [];
    private(set) array $zoneModifiers = [];
    private(set) array $cardTypes = [];
    private(set) array $maxLengths = [];

    public function __construct(
        private readonly AffinityRepositoryInterface $affinityRepository,
    ) {}

    public function setAddData(): void
    {
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

        $this->card = [
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
        $this->card = [];
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];
        $this->maxLengths = [];

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
