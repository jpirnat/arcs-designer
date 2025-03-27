<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Presentation;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class IndexView
{
    public function __construct(
        private RendererInterface $renderer,
    ) {}

    /**
     * Show a card page.
     */
    public function card(): ResponseInterface
    {
        $content = $this->renderer->render(
            'html/card.twig',
            [
                'title' => 'Cards',
            ],
        );

        return new HtmlResponse($content);
    }

    /**
     * Show the /cards page.
     */
    public function cards(): ResponseInterface
    {
        $content = $this->renderer->render(
            'html/cards.twig',
            [
                'title' => 'Cards',
            ],
        );

        return new HtmlResponse($content);
    }

    /**
     * Show the error page.
     */
    public function error(): ResponseInterface
    {
        $content = $this->renderer->render(
            'html/error.twig',
            [
                'title' => 'Error',
            ],
        );

        return new HtmlResponse($content);
    }
}
