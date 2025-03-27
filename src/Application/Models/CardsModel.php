<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface;

final class CardsModel
{
    private(set) array $cards = [];

    public function __construct(
        private readonly CardRepositoryInterface $cardRepository,
    ) {}

    public function setData(): void
    {
        $this->cards = [];

        $cards = $this->cardRepository->getAll();
        foreach ($cards as $card) {
            $this->cards[] = [
                'id' => $card->id->value,
                'name' => $card->name,
            ];
        }
    }
}
