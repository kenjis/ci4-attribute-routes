<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\AttributeReader;

use Kenjis\CI4\AttributeRoutes\Route;
use Kenjis\CI4\AttributeRoutes\TestCase;
use Tests\Support\Controllers\MethodController;

/**
 * @internal
 */
final class MethodReaderTest extends TestCase
{
    public function testGetMethodRoutes(): void
    {
        $reader = new MethodReader();

        $routes = $reader->getRoutes(MethodController::class);

        $this->assertCount(2, $routes);
        $this->assertInstanceOf(Route::class, $routes[0]);
        $this->assertInstanceOf(Route::class, $routes[1]);
    }
}
