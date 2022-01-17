<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\Config\Services;

use function is_file;

class ControllerFinder
{
    /**
     * @var string[]
     */
    private array $namespaces;

    private FileLocator $locator;

    /**
     * @param string[] $namespaces namespaces to search
     */
    public function __construct(array $namespaces)
    {
        $this->namespaces = $namespaces;
        $this->locator    = Services::locator();
    }

    /**
     * @return class-string[]
     */
    public function find(): array
    {
        $classes = [];

        foreach ($this->namespaces as $namespace) {
            $files = $this->locator->listNamespaceFiles($namespace, 'Controllers');

            foreach ($files as $file) {
                if (is_file($file)) {
                    $classnameOrEmpty = $this->locator->getClassname($file);

                    if ($classnameOrEmpty !== '') {
                        /** @var class-string $classname */
                        $classname = $classnameOrEmpty;

                        $classes[] = $classname;
                    }
                }
            }
        }

        return $classes;
    }
}
