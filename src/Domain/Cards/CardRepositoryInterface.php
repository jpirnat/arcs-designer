<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Cards;

interface CardRepositoryInterface
{
    /**
     * @throws CardNotFoundException if no card exists with this id.
     */
    public function getById(CardId $cardId): Card;

    public function save(Card $card): void;
}
