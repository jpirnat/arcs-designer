<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Affinities;

interface AffinityRepositoryInterface
{
    public function getByAspects(
        bool $purple,
        bool $red,
        bool $orange,
        bool $yellow,
        bool $green,
        bool $blue,
    ): Affinity;

    /**
     * @return Affinity[] Indexed by id.
     */
    public function getAll(): array;
}
