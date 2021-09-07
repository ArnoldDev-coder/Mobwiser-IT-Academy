<?php

namespace Kernel\Extensions;

use Kernel\Router\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap5View;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }
    public function paginate(Pagerfanta $paginatedResults, string $route,array $routerParams = [], array $queryArgs = []): string
    {
        $view = new TwitterBootstrap5View();
        return $view->render($paginatedResults, function (int $page) use ($route,$routerParams, $queryArgs) {
            if ($page > 1) {
                $queryArgs['page'] = $page;
            }
            return $this->router->generateUri($route,$routerParams , $queryArgs);
        });
    }
}