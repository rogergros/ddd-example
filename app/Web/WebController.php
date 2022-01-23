<?php

declare(strict_types=1);

namespace DDDExample\App\Web;

use DDDExample\App\AppController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

abstract class WebController implements AppController
{
    public function __construct(private Environment $twig, private UrlGeneratorInterface $urlGenerator)
    {
    }

    protected function render(string $view, array $parameters = []): string
    {
        return $this->twig->render($view, $parameters);
    }

    protected function renderResponse(string $view, array $parameters = []): Response
    {
        $content = $this->render($view, $parameters);

        return new Response($content);
    }

    protected function redirect(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate($route, $parameters),
            $status
        );
    }
}
