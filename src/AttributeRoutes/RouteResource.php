<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RouteResource extends AbstractRouteRest
{
    /**
     * @var string[]
     */
    protected array $validMethods = [
        'index',
        'show',
        'new',
        'create',
        'edit',
        'update',
        'delete',
    ];

    protected string $codeTemplate = "\$routes->resource('%s', %s);";
}
