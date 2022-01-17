<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\AttributeReader;

use Kenjis\CI4\AttributeRoutes\RouteGroup;
use Kenjis\CI4\AttributeRoutes\RoutePresenter;
use Kenjis\CI4\AttributeRoutes\RouteResource;
use Kenjis\CI4\AttributeRoutes\TestCase;
use Tests\Support\Controllers\GroupController;
use Tests\Support\Controllers\PresenterController;
use Tests\Support\Controllers\ResourceController;

/**
 * @internal
 */
final class ClassReaderTest extends TestCase
{
    private ClassReader $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = new ClassReader();
    }

    public function testGetGroupRoutes(): void
    {
        $routes = $this->reader->getGroupRoutes(GroupController::class);

        $this->assertInstanceOf(RouteGroup::class, $routes[0]);
    }

    public function testGetResourceRoutes(): void
    {
        $routes = $this->reader->getResourceRoutes(ResourceController::class);

        $this->assertInstanceOf(RouteResource::class, $routes[0]);
    }

    public function testGetPresenterRoutes(): void
    {
        $routes = $this->reader->getPresenterRoutes(PresenterController::class);

        $this->assertInstanceOf(RoutePresenter::class, $routes[0]);
    }
}
