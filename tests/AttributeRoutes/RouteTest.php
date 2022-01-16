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
            $routes->get('method/index', 'Tests\Support\Controllers\MethodController::getIndex', [
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
            $routes->get('method/test', 'Tests\Support\Controllers\MethodController::test', [
            ]);
            $routes->post('method/test', 'Tests\Support\Controllers\MethodController::test', [
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
            $routes->get('method/index', 'Tests\Support\Controllers\MethodController::getIndex', [
              'as' => 'test',
              'filter' => [
                0 => 'admin-auth',
                1 => 'App\\Filters\\SomeFilter',
              ],
            ]);

            CODE;
        $this->assertSame($expected, $code);
    }
}
