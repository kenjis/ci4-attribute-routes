<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use function array_merge;
use function assert;
use function count;
use function implode;
use function in_array;
use function sprintf;
use function str_replace;

abstract class AbstractRouteRest
{
    use VarExportTrait;

    protected string $name;

    /**
     * @var array<string, mixed>
     */
    protected array $options;

    /**
     * @var class-string|null
     */
    protected ?string $controller;

    /**
     * @var string[]|null
     */
    protected ?array $only;

    /**
     * @var string[]
     */
    protected array $validMethods;

    protected string $codeTemplate;

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
     * @return string[]|null
     */
    public function getOnly(): ?array
    {
        return $this->only;
    }

    /**
     * @param class-string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function isValidMethod(string $method): bool
    {
        return in_array($method, $this->validMethods, true);
    }

    /**
     * @param string[] $only
     */
    public function setOnly(array $only): void
    {
        foreach ($only as $method) {
            assert($this->isValidMethod($method));
        }

        if (count($only) === count($this->validMethods)) {
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
            $this->codeTemplate,
            $this->getName(),
            $options
        ) . "\n";
    }
}
