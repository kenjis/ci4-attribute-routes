<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

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
}
