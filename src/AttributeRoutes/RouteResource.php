<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

use function array_merge;
use function assert;
use function count;
use function implode;
use function in_array;
use function sprintf;
use function str_replace;

#[Attribute(Attribute::TARGET_CLASS)]
class RouteResource
{
    use VarExportTrait;

    private string $name;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * @var class-string|null
     */
    private ?string $controller;

    /**
     * @var string[]|null
     */
    private ?array $only;

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
     * @param class-string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param string[] $only
     */
    public function setOnly(array $only): void
    {
        $validMethods = ['index', 'show', 'new', 'create', 'edit', 'update', 'delete'];

        foreach ($only as $method) {
            assert(in_array($method, $validMethods, true));
        }

        if (count($only) === count($validMethods)) {
            $only = [];
        }

        $this->only = $only;
    }

    public function asCode(): string
    {
        assert(
            $this->controller !== null,
            'You must set $controller with setController().'
        );
        assert(
            $this->only !== null,
            'You must set $only with setOnly().'
        );

        $options = ['controller' => $this->controller];
        $options = array_merge($options, $this->options);
        $options = str_replace(
            ["\n", '  ', ',]', '\\\\', "[ '", ', ]'],
            [' ', '', ']', '\\', "['", ']'],
            $this->varExport($options)
        );

        // add only
        if ($this->only !== []) {
            $options = str_replace(
                ']',
                ", 'only' => ['" . implode("', '", $this->only) . "']]",
                $options
            );
        }

        return sprintf(
            "\$routes->resource('%s', %s);",
            $this->getName(),
            $options
        ) . "\n";
    }
}
