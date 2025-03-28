<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\CardIterations;

use Jp\ArcsDesigner\Domain\Cards\CardId;

interface CardIterationRepositoryInterface
{
    /**
     * @throws CardIterationNotFoundException if no card iteration exists with this id.
     */
    public function getById(CardIterationId $iterationId): CardIteration;

    /**
     * @return CardIteration[] Indexed by id. Ordered by created at.
     */
    public function getByCard(CardId $cardId): array;

    public function save(CardIteration $iteration): void;

    public function setCurrent(CardId $cardId, CardIterationId $iterationId): void;

    public function getCurrent(CardId $cardId): CardIteration;
}
