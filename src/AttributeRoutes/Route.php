<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

use function assert;
use function sprintf;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    use VarExportTrait;

    private string $uri;

    /**
     * @var string[]
     */
    private array $methods;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    private ?string $controllerMethod;

    /**
     * @param string[]             $methods
     * @param array<string, mixed> $options
     */
    public function __construct(string $uri, array $methods = [], array $options = [])
    {
        $this->uri     = $uri;
        $this->methods = $methods;
        $this->options = $options;
    }

    public function setControllerMethod(string $controllerMethod): void
    {
        $this->controllerMethod = $controllerMethod;
    }

    public function asCode(): string
    {
        assert(
            $this->controllerMethod !== null,
            'You must set $controllerMethod with setControllerMethod().'
        );

        $code = '';

        foreach ($this->methods as $method) {
            $code .= sprintf(
                "\$routes->%s('%s', '%s', %s);",
                $method,
                $this->uri,
                $this->controllerMethod,
                $this->varExport($this->options)
            ) . "\n";
        }

        return $code;
    }
}
