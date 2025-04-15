<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain\SetCards;

use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Sets\SetId;

final readonly class SetCard
{
    public function __construct(
        private(set) SetId $setId,
        private(set) CardId $cardId,
    ) {}
}
