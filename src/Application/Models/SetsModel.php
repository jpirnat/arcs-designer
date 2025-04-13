<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;

final class SetsModel
{
    private(set) array $sets = [];

    public function __construct(
        private readonly SetRepositoryInterface $setRepository,
    ) {}

    public function setData(): void
    {
        $this->sets = [];

        $sets = $this->setRepository->getAll();
        foreach ($sets as $set) {
            $this->sets[] = [
                'id' => $set->id->value,
                'name' => $set->name,
            ];
        }
    }
}
