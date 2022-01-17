<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

/**
 * @internal
 */
final class ControllerFinderTest extends TestCase
{
    public function testFind(): void
    {
        $namespaces = ['Tests\Support'];
        $finder     = new ControllerFinder($namespaces);

        $controllers = $finder->find();

        $this->assertCount(4, $controllers);
    }
}
