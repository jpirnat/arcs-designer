<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Sets\Set;
use Jp\ArcsDesigner\Domain\Sets\SetId;
use Jp\ArcsDesigner\Domain\Sets\SetNotFoundException;
use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;

final class SetModel
{
    private(set) array $set = [];
    private(set) array $cards = [];
    private(set) array $maxLengths = [];
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly SetRepositoryInterface $setRepository,
        private readonly CardRepositoryInterface $cardRepository,
        private readonly CardIterationRepositoryInterface $iterationRepository,
    ) {}

    public function setAddData(): void
    {
        $this->set = [
            'id' => null,
            'name' => '',
        ];
        $this->cards = [];
        $this->maxLengths = [];

        $this->setCommonData();
    }

    public function setEditData(string $setId): void
    {
        $this->set = [];
        $this->cards = [];
        $this->maxLengths = [];

        $setId = new SetId((int) $setId);
        try {
            $set = $this->setRepository->getById($setId);
        } catch (SetNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->set = [
            'id' => $set->id->value,
            'name' => $set->name,
        ];

        $cards = $this->cardRepository->getBySet($set->id);
        foreach ($cards as $card) {
            $iteration = $this->iterationRepository->getCurrent($card->id);

            $this->cards[] = [
                'id' => $card->id->value,
                'name' => $iteration->name,
            ];
        }

        $this->setCommonData();
    }

    private function setCommonData(): void
    {
        $this->maxLengths = [
            'name' => Set::MAX_LENGTH_NAME,
        ];
    }
}
