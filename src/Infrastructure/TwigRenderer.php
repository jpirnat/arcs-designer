<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Presentation\RendererInterface;
use Twig\Environment;

final readonly class TwigRenderer implements RendererInterface
{
    public function __construct(
        private Environment $twig,
    ) {}

    /**
     * @inheritDoc
     */
    public function render(string $template, array $data = []): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->twig->render($template, $data);
    }
}
