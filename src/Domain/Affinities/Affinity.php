<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Affinities;

final readonly class Affinity
{
    public function __construct(
        private(set) AffinityId $id,
        private(set) string $name,
        private(set) bool $purple,
        private(set) bool $red,
        private(set) bool $orange,
        private(set) bool $yellow,
        private(set) bool $green,
        private(set) bool $blue,
    ) {}
}
