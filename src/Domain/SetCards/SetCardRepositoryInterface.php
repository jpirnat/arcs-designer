<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\SetCards;

use Jp\ArcsDesigner\Domain\Cards\CardId;

interface SetCardRepositoryInterface
{
    /**
     * @return SetCard[]
     */
    public function getByCard(CardId $cardId): array;

    public function save(SetCard $setCard): void;

    public function deleteByCard(CardId $cardId): void;
}
