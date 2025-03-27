<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Cards;

interface CardRepositoryInterface
{
    /**
     * @throws CardNotFoundException if no card exists with this id.
     */
    public function getById(CardId $cardId): Card;

    /**
     * @return Card[] Indexed by id.
     */
    public function getAll(): array;

    public function save(Card $card): void;
}
