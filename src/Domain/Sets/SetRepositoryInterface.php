<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Sets;

interface SetRepositoryInterface
{
    public function save(Set $set): void;
}
