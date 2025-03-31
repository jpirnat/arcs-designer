<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\Cards;

final class Card
{
    private(set) readonly CardId $id;
    public Status $status;

    public function __construct(
        CardId $id,
        Status $status,
    ) {
        $this->id = $id;
        $this->status = $status;
    }
}
