<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Tests\Support\Controllers\ResourceController;

/**
 * @internal
 */
final class RoutePresenterTest extends TestCase
{
    /**
     * @var string
     */
    private $name = 'presenter';

    public function testGetNameAndGetOptions(): void
    {
        $presenter = new RoutePresenter($this->name);

        $this->assertSame($this->name, $presenter->getName());
    }

    public function testAsCodeWithOptions(): void
    {
        $presenter = new RoutePresenter($this->name);
        $presenter->setController(ResourceController::class);
        $presenter->setOnly(['new', 'create', 'index', 'show']);

        $expected = <<<'CODE'
            $routes->presenter('presenter', ['controller' => 'Tests\Support\Controllers\ResourceController', 'only' => ['new', 'create', 'index', 'show']]);

            CODE;
        $this->assertSame($expected, $presenter->asCode());
    }

    public function testAsCodeWithAllMethods(): void
    {
        $presenter = new RoutePresenter($this->name);
        $presenter->setController(ResourceController::class);
        $presenter->setOnly(['index', 'show', 'create', 'update', 'new', 'edit', 'delete', 'remove']);

        $expected = <<<'CODE'
            $routes->presenter('presenter', ['controller' => 'Tests\Support\Controllers\ResourceController']);

            CODE;
        $this->assertSame($expected, $presenter->asCode());
    }
}
