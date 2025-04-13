<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Sets\InvalidNameException;
use Jp\ArcsDesigner\Domain\Sets\Set;
use Jp\ArcsDesigner\Domain\Sets\SetId;
use Jp\ArcsDesigner\Domain\Sets\SetNotFoundException;
use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;

final class EditSetModel
{
    private(set) string $errorMessage = '';

    public function __construct(
        private readonly SetRepositoryInterface $setRepository,
    ) {}

    public function addSet(
        string $name,
    ): void {
        $this->errorMessage = '';

        try {
            $set = new Set(
                new SetId(),
                $name,
            );
        } catch (InvalidNameException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->setRepository->save($set);
    }

    public function editSet(
        string $setId,
        string $name,
    ): void {
        $this->errorMessage = '';

        $setId = new SetId((int) $setId);

        try {
            $set = $this->setRepository->getById($setId);
        } catch (SetNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        try {
            $set->name = $name;
        } catch (InvalidNameException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->setRepository->save($set);
    }
}
