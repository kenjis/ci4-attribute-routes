<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Tests\Support\Controllers\GroupController;
use Tests\Support\Controllers\MethodController;
use Tests\Support\Controllers\PresenterController;
use Tests\Support\Controllers\ResourceController;

/**
 * @internal
 */
final class AttributeReaderTest extends TestCase
{
    private AttributeReader $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = new AttributeReader();
    }

    public function testGetRoutesMethod(): void
    {
        $routes = $this->reader->getRoutes(MethodController::class);

        $this->assertCount(2, $routes);
    }

    public function testGetRoutesGroup(): void
    {
        $routes = $this->reader->getRoutes(GroupController::class);

        $this->assertCount(1, $routes);
        $this->assertInstanceOf(RouteGroup::class, $routes[0]);
    }

    public function testGetRoutesResource(): void
    {
        $routes = $this->reader->getRoutes(ResourceController::class);

        $this->assertCount(1, $routes);
        $this->assertInstanceOf(RouteResource::class, $routes[0]);
        $this->assertSame(['new', 'create', 'index', 'show'], $routes[0]->getOnly());
    }

    public function testGetRoutesPresenter(): void
    {
        $routes = $this->reader->getRoutes(PresenterController::class);

        $this->assertCount(1, $routes);
        $this->assertInstanceOf(RoutePresenter::class, $routes[0]);
        $this->assertSame(['new', 'create', 'index', 'remove', 'delete'], $routes[0]->getOnly());
    }
}
