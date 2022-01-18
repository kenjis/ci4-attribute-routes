<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Tests\Support\Controllers\MethodController;

/**
 * @internal
 */
final class RouteTest extends TestCase
{
    public function testAsCodeOneRoute(): void
    {
        $route = new Route('method/index', ['get']);
        $route->setControllerMethod(MethodController::class . '::getIndex');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('method/index', '\Tests\Support\Controllers\MethodController::getIndex', [
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }

    public function testAsCodeTwoRoutes(): void
    {
        $route = new Route('method/test', ['get', 'post']);
        $route->setControllerMethod(MethodController::class . '::test');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('method/test', '\Tests\Support\Controllers\MethodController::test', [
            ]);
            $routes->post('method/test', '\Tests\Support\Controllers\MethodController::test', [
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }

    public function testAsCodeOneRouteWithOptions(): void
    {
        $route = new Route(
            'method/index',
            ['get'],
            [
                'as'     => 'test',
                'filter' => ['admin-auth', 'App\Filters\SomeFilter'],
            ],
        );
        $route->setControllerMethod(MethodController::class . '::getIndex');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('method/index', '\Tests\Support\Controllers\MethodController::getIndex', [
              'as' => 'test',
              'filter' => [
                0 => 'admin-auth',
                1 => 'App\\Filters\\SomeFilter',
              ],
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }

    public function testPlaceholder(): void
    {
        $route = new Route('news/(:segment)', ['get']);
        $route->setControllerMethod('App\Controllers\News::view');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('news/(:segment)', '\App\Controllers\News::view/$1', [
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }

    public function testPlaceholdersConsecutive(): void
    {
        $route = new Route('products/([a-z]+)/(\d+)', ['get']);
        $route->setControllerMethod('App\Controllers\Products::show');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('products/([a-z]+)/(\d+)', '\App\Controllers\Products::show/$1/$2', [
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }

    public function testPlaceholdersApart(): void
    {
        $route = new Route('users/(:num)/gallery(:any)', ['get']);
        $route->setControllerMethod('App\Controllers\Galleries::showUserGallery');

        $code = $route->asCode();

        $expected = <<<'CODE'
            $routes->get('users/(:num)/gallery(:any)', '\App\Controllers\Galleries::showUserGallery/$1/$2', [
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }
}
