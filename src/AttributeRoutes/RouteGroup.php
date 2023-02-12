<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

use function preg_replace;
use function sprintf;
use function str_replace;

#[Attribute(Attribute::TARGET_CLASS)]
final class RouteGroup
{
    use VarExportTrait;

    private string $name;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * @var Route[]
     */
    private array $routes;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name    = $name;
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param Route[] $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function asCode(): string
    {
        $options = str_replace(
            ["\n", '  ', ',]'],
            ['', '', ']'],
            $this->varExport($this->options)
        );

        $code = sprintf(
            "\$routes->group('%s', %s, static function (\$routes) {",
            $this->getName(),
            $options
        ) . "\n";

        $routeCode = '';

        foreach ($this->routes as $route) {
            $routeCode .= $route->asCode();
        }

        $routeCode = preg_replace('/^/m', '    ', $routeCode);
        $code .= $routeCode;

        return $code . "});\n";
    }
}
