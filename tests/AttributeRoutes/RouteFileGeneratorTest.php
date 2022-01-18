<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use function unlink;

/**
 * @internal
 */
final class RouteFileGeneratorTest extends TestCase
{
    /**
     * @var string[]
     */
    private array $namespaces = ['Tests\Support'];

    private string $routesFile = __DIR__ . '/RoutesFromAttribute.php';
    private RouteFileGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = new RouteFileGenerator($this->namespaces, $this->routesFile);
    }

    public function testGetRoutes(): void
    {
        $routes = $this->generator->getRoutes();

        $this->assertCount(5, $routes);
    }

    public function testGenerate(): void
    {
        $message = $this->generator->generate();

        $this->assertStringEndsWith('AttributeRoutes/RoutesFromAttribute.php generated.', $message);
        $this->assertFileExists($this->routesFile);

        unlink($this->routesFile);
    }

    public function testGetRoutesCode(): void
    {
        $code = $this->generator->getRoutesCode();

        $expected = <<<'CODE'
            $routes->group('group', ['filter' => 'auth'], static function ($routes) {
                $routes->get('a', '\Tests\Support\Controllers\GroupController::getA', [
                ]);
                $routes->post('a', '\Tests\Support\Controllers\GroupController::postA', [
                ]);
            });
            $routes->get('method/a', '\Tests\Support\Controllers\MethodController::getA', [
            ]);
            $routes->get('method/b', '\Tests\Support\Controllers\MethodController::getB', [
            ]);
            $routes->presenter('presenter', ['controller' => 'Tests\Support\Controllers\PresenterController', 'only' => ['new', 'create', 'index', 'remove', 'delete']]);
            $routes->resource('photos', ['controller' => 'Tests\Support\Controllers\ResourceController', 'websafe' => 1, 'only' => ['new', 'create', 'index', 'show']]);

            CODE;
        $this->assertSame($expected, $code);
    }
}
