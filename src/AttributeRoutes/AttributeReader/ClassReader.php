<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\AttributeReader;

use Kenjis\CI4\AttributeRoutes\RouteGroup;
use Kenjis\CI4\AttributeRoutes\RoutePresenter;
use Kenjis\CI4\AttributeRoutes\RouteResource;
use ReflectionClass;

final class ClassReader
{
    /**
     * @param class-string $class
     *
     * @return RouteGroup[]
     */
    public function getGroupRoutes(string $class): array
    {
        return $this->getClassRoutes($class, RouteGroup::class);
    }

    /**
     * @param class-string $class
     *
     * @return RouteResource[]
     */
    public function getResourceRoutes(string $class): array
    {
        return $this->getClassRoutes($class, RouteResource::class);
    }

    /**
     * @param class-string $class
     *
     * @return RoutePresenter[]
     */
    public function getPresenterRoutes(string $class): array
    {
        return $this->getClassRoutes($class, RoutePresenter::class);
    }

    /**
     * @param class-string    $class Controller class
     * @param class-string<T> $route Route class
     *
     * @return list<T>
     *
     * @template T
     */
    private function getClassRoutes(string $class, string $route): array
    {
        $reflection = new ReflectionClass($class);

        $routes = [];

        $attributes = $reflection->getAttributes($route);

        if ($attributes === []) {
            return [];
        }

        foreach ($attributes as $attribute) {
            $route = $attribute->newInstance();

            $routes[] = $route;
        }

        return $routes;
    }
}
