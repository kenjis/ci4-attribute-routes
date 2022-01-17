<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RoutePresenter extends AbstractRouteRest
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
        'remove',
        'delete',
    ];

    protected string $codeTemplate = "\$routes->presenter('%s', %s);";
}
