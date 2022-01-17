<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Tests\Support\Controllers\GroupController;

/**
 * @internal
 */
final class RouteGroupTest extends TestCase
{
    public function testGetNameAndGetOptions(): void
    {
        $name    = 'group';
        $options = ['filter' => 'auth'];
        $group   = new RouteGroup($name, $options);

        $this->assertSame($name, $group->getName());
        $this->assertSame($options, $group->getOptions());
    }

    public function testAsCodeWithNoOptions(): void
    {
        $name  = 'group';
        $group = new RouteGroup($name);

        $routes = $this->getRoutes();
        $group->setRoutes($routes);

        $expected = <<<'CODE'
            $routes->group('group', [], static function ($routes) {
                $routes->get('a', 'Tests\Support\Controllers\GroupController::getA', [
                ]);
                $routes->post('a', 'Tests\Support\Controllers\GroupController::postA', [
                ]);
            });

            CODE;
        $this->assertSame($expected, $group->asCode());
    }

    /**
     * @return Route[]
     */
    private function getRoutes(): array
    {
        $route1 = new Route('a', ['get']);
        $route1->setControllerMethod(GroupController::class . '::getA');
        $routes[] = $route1;
        $route1   = new Route('a', ['post']);
        $route1->setControllerMethod(GroupController::class . '::postA');
        $routes[] = $route1;

        return $routes;
    }

    public function testAsCodeWithOptions(): void
    {
        $name    = 'group';
        $options = ['filter' => 'auth'];
        $group   = new RouteGroup($name, $options);

        $routes = $this->getRoutes();
        $group->setRoutes($routes);

        $expected = <<<'CODE'
            $routes->group('group', ['filter' => 'auth'], static function ($routes) {
                $routes->get('a', 'Tests\Support\Controllers\GroupController::getA', [
                ]);
                $routes->post('a', 'Tests\Support\Controllers\GroupController::postA', [
                ]);
            });

            CODE;
        $this->assertSame($expected, $group->asCode());
    }
}
