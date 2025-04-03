<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\Cards\CardId;

final class SetAsCurrentModel
{
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly CardIterationRepositoryInterface $iterationRepository,
    ) {}

    public function setData(
        string $cardId,
        string $iterationId,
    ): void {
        $cardId = new CardId((int) $cardId);
        $iterationId = new CardIterationId((int) $iterationId);

        try {
            $iteration = $this->iterationRepository->getById($iterationId);
        } catch (CardIterationNotFoundException) {
            return;
        }

        if ($cardId->value !== $iteration->cardId->value) {
            $this->errorMessage = "Error: That's a design for a different card.";
            return;
        }

        $this->iterationRepository->setCurrent(
            $cardId,
            $iterationId,
        );
    }
}
