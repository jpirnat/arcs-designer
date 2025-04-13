<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Sets;

interface SetRepositoryInterface
{
    /**
     * @throws SetNotFoundException if no set exists with this id.
     */
    public function getById(SetId $setId): Set;

    /**
     * @return Set[] Indexed by id.
     */
    public function getAll(): array;

    public function save(Set $set): void;
}
