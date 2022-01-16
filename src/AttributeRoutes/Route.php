<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

use function var_export;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    private string $uri;

    /**
     * @var string[]
     */
    private array $methods;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    private string $controllerMethod;

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
        \assert(
            $this->controllerMethod !== null,
            'You must set $controllerMethod with setControllerMethod().'
        );

        $code = '';

        foreach ($this->methods as $method) {
            $code .= \sprintf(
                '$routes->%s(\'%s\', \'%s\', %s);',
                $method,
                $this->uri,
                $this->controllerMethod,
                $this->varExport($this->options, true)
            ) . "\n";
        }

        return $code;
    }

    /**
     * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
     *
     * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
     *
     * @param mixed $expression
     *
     * @return string|null
     *
     * @see https://www.php.net/manual/en/function.var-export.php
     */
    private function varExport($expression, bool $return = false)
    {
        $export = \var_export($expression, true);

        $patterns = [
            '/array \(/'                            => '[',
            '/^([ ]*)\)(,?)$/m'                     => '$1]$2',
            "/=>[ ]?\n[ ]+\\[/"                     => '=> [',
            "/([ ]*)(\\'[^\\']+\\') => ([\\[\\'])/" => '$1$2 => $3',
        ];
        $export = \preg_replace(\array_keys($patterns), \array_values($patterns), $export);

        if ((bool) $return) {
            return $export;
        }

        echo $export;

        return '';
    }
}
