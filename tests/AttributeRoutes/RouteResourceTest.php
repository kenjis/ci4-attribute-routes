<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Tests\Support\Controllers\ResourceController;

/**
 * @internal
 */
final class RouteResourceTest extends TestCase
{
    public function testGetNameAndGetOptions(): void
    {
        $name     = 'photos';
        $options  = ['websafe' => 1];
        $resource = new RouteResource($name, $options);

        $this->assertSame($name, $resource->getName());
        $this->assertSame($options, $resource->getOptions());
    }

    public function testSetOnlyAndGetOnly(): void
    {
        $name     = 'photos';
        $resource = new RouteResource($name);

        $resource->setOnly(['index', 'show']);

        $this->assertSame(['index', 'show'], $resource->getOnly());
    }

    public function testIsValidMethod(): void
    {
        $name     = 'photos';
        $resource = new RouteResource($name);

        $this->assertFalse($resource->isValidMethod('bad'));
        $this->assertTrue($resource->isValidMethod('index'));
    }

    public function testAsCodeWithOptions(): void
    {
        $name     = 'photos';
        $options  = ['websafe' => 1];
        $resource = new RouteResource($name, $options);
        $resource->setController(ResourceController::class);
        $resource->setOnly(['new', 'create', 'index', 'show']);

        $expected = <<<'CODE'
            $routes->resource('photos', ['controller' => 'Tests\Support\Controllers\ResourceController', 'websafe' => 1, 'only' => ['new', 'create', 'index', 'show']]);

            CODE;
        $this->assertSame($expected, $resource->asCode());
    }

    public function testAsCodeWithAllMethods(): void
    {
        $name     = 'photos';
        $resource = new RouteResource($name);
        $resource->setController(ResourceController::class);
        $resource->setOnly(['index', 'show', 'create', 'update', 'new', 'edit', 'delete']);

        $expected = <<<'CODE'
            $routes->resource('photos', ['controller' => 'Tests\Support\Controllers\ResourceController']);

            CODE;
        $this->assertSame($expected, $resource->asCode());
    }
}
