<?php

declare(strict_types=1);

namespace DDDExample\App\Web;

use DDDExample\App\AppController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class WebController implements AppController
{
    public function __construct(private Environment $twig)
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
}
