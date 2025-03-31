<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Application\Models;

use Jp\ArcsDesigner\Application\SessionVariables;
use Jp\ArcsDesigner\Domain\Users\UserId;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class BaseModel
{
    public function __construct(
        private(set) readonly SessionInterface $session,
    ) {}

    public ?UserId $userId {
        get => $this->session->has(SessionVariables::USER_ID)
            ? new UserId($this->session->get(SessionVariables::USER_ID))
            : null;
    }
}
