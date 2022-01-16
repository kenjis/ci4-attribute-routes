<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

/**
 * @internal
 */
final class RouteGroupTest extends TestCase
{
    public function test(): void
    {
        $name = 'api';
        $options = ['filter' => 'api-auth'];
        $group = new RouteGroup($name, $options);

        $this->assertSame($name, $group->getName());
        $this->assertSame($options, $group->getOptions());
    }
}
