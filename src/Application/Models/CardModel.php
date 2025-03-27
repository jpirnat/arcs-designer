<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;

final class CardModel
{
    private(set) array $affinities = [];
    private(set) array $speedModifiers = [];
    private(set) array $zoneModifiers = [];
    private(set) array $cardTypes = [];

    public function __construct(
        private readonly AffinityRepositoryInterface $affinityRepository,
    ) {}

    public function setAddData(): void
    {
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];

        $this->setCommonData();
    }

    public function setEditData(string $cardId): void
    {
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];

        $this->setCommonData();
    }

    private function setCommonData(): void
    {
        $this->affinities = [];
        $this->speedModifiers = [];
        $this->zoneModifiers = [];
        $this->cardTypes = [];

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
