<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Kenjis\CI4\AttributeRoutes\AttributeReader\ClassReader;
use Kenjis\CI4\AttributeRoutes\AttributeReader\MethodReader;
use ReflectionClass;
use ReflectionMethod;

use function array_merge;

class AttributeReader
{
    private ClassReader $classReader;
    private MethodReader $methodReader;

    public function __construct()
    {
        $this->classReader  = new ClassReader();
        $this->methodReader = new MethodReader();
    }

    /**
     * @param class-string $class
     *
     * @return list<Route|RouteGroup|RoutePresenter|RouteResource>
     */
    public function getRoutes(string $class)
    {
        $groupRoutes     = $this->getGroup($class);
        $resourceRoutes  = $this->getResource($class);
        $presenterRoutes = $this->getPresenter($class);

        $routes = array_merge($groupRoutes, $resourceRoutes, $presenterRoutes);
        if ($routes !== []) {
            return $routes;
        }

        return $this->methodReader->getMethodRoutes($class);
    }

    /**
     * @param class-string $class
     *
     * @return RouteGroup[]
     */
    private function getGroup(string $class): array
    {
        $groups = $this->classReader->getGroupRoutes($class);

        foreach ($groups as $group) {
            $methodRoutes = $this->methodReader->getMethodRoutes($class);
            $group->setRoutes($methodRoutes);
        }

        return $groups;
    }

    /**
     * @param class-string $class
     *
     * @return RouteResource[]
     */
    private function getResource(string $class): array
    {
        $resources = $this->classReader->getResourceRoutes($class);

        if ($resources === []) {
            return [];
        }

        $reflection = new ReflectionClass($class);

        foreach ($resources as $resource) {
            $resource->setController($reflection->getName());

            $only = [];

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodName = $method->getName();

                if ($resource->isValidMethod($methodName)) {
                    $only[] = $methodName;
                }
            }

            $resource->setOnly($only);
        }

        return $resources;
    }

    /**
     * @param class-string $class
     *
     * @return RoutePresenter[]
     */
    private function getPresenter(string $class): array
    {
        $presenters = $this->classReader->getPresenterRoutes($class);

        if ($presenters === []) {
            return [];
        }

        $reflection = new ReflectionClass($class);

        foreach ($presenters as $presenter) {
            $presenter->setController($reflection->getName());

            $only = [];

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodName = $method->getName();

                if ($presenter->isValidMethod($methodName)) {
                    $only[] = $methodName;
                }
            }

            $presenter->setOnly($only);
        }

        return $presenters;
    }
}
