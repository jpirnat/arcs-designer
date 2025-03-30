<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class RedirectView
{
    public function cards(): ResponseInterface
    {
        return new RedirectResponse('/cards');
    }
}
