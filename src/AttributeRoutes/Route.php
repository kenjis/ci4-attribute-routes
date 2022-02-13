<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

use function assert;
use function count;
use function is_countable;
use function preg_match_all;
use function sprintf;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Route
{
    use VarExportTrait;

    private string $uri;

    /**
     * @var string[] HTTP methods
     */
    private array $methods;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    private ?string $controllerMethod = null;

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
        $this->controllerMethod = '\\' . $controllerMethod . $this->getArgs();
    }

    /**
     * Returns the path like `/$1/$2` for placeholders
     */
    private function getArgs(): string
    {
        preg_match_all('/\(.+?\)/', $this->uri, $matches);
        $count = is_countable($matches[0]) ? count($matches[0]) : 0;

        $args = '';

        if ($count > 0) {
            for ($i = 1; $i <= $count; $i++) {
                $args .= '/$' . $i;
            }
        }

        return $args;
    }

    public function asCode(): string
    {
        assert(
            $this->controllerMethod !== null,
            'You must set $controllerMethod with setControllerMethod().'
        );

        $code = '';

        foreach ($this->methods as $method) {
            if ($this->options === []) {
                $code .= sprintf(
                    "\$routes->%s('%s', '%s');",
                    $method,
                    $this->uri,
                    $this->controllerMethod,
                ) . "\n";

                continue;
            }

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
