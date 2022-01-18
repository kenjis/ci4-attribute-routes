# CodeIgniter4 Attribute Routes

This package generates Routes File from the Attribute Routes in your Controllers.

You can set routes in your Controllers, and disable Auto Routing.

## Requirements

- CodeIgniter 4.1.7 or later
- Composer
- PHP 8.0 or later

## Installation

```sh-session
$ composer require kenjis/ci4-attribute-routes:1.x-dev
```

## Configuration

1. Add the following code to the bottom of your `app/Config/Routes.php` file:
```php
/*
 * Attribute Routes
 *
 * To update the route file, run the following command:
 * $ php spark route:update
 *
 * @see https://github.com/kenjis/ci4-attribute-routes
 */
if (file_exists(APPPATH . 'Config/RoutesFromAttribute.php')) {
    require APPPATH . 'Config/RoutesFromAttribute.php';
}
```

2. Disable auto routing and enable route priority:
```diff
--- a/app/Config/Routes.php
+++ b/app/Config/Routes.php
@@ -22,7 +22,8 @@ $routes->setDefaultController('Home');
 $routes->setDefaultMethod('index');
 $routes->setTranslateURIDashes(false);
 $routes->set404Override();
-$routes->setAutoRoute(true);
+$routes->setAutoRoute(false);
+$routes->setPrioritize();
```

This is optional, but strongly recommended.

## Quick Start

### 1. Add Attribute Routes to your Controllers

Add `#[Route()]` attributes to your Controller methods.

```php
<?php
namespace App\Controllers;

use Kenjis\CI4\AttributeRoutes\Route;

class News extends BaseController
{
    #[Route('news', methods: ['get'])]
    public function index()
    {
        ...
    }
}
```

### 2. Update Routes File

```sh-session
$ php spark route:update
```

`APPPATH/Config/RoutesFromAttribute.php` is generated.

Check your routes with the `php spark routes` command.

## Route Attributes

### Route

```php
#[Route('news', methods: ['get'])]
```
```php
#[Route('news/create', methods: ['get', 'post'])]
```
```php
#[Route('news/(:segment)', methods: ['get'], options: ['priority' => 1])]
```

### RouteGroup

```php
use Kenjis\CI4\AttributeRoutes\RouteGroup;

#[RouteGroup('', options: ['filter' => 'auth'])]
class GroupController extends BaseController
{
    #[Route('group/a', methods: ['get'])]
    public function getA(): void
    {
        ...
    }
    ...
}
```

### RouteResource

```php
use Kenjis\CI4\AttributeRoutes\RouteResource;

#[RouteResource('photos', options: ['websafe' => 1])]
class ResourceController extends ResourceController
{
    ...
}
```

### RoutePresenter

```php
use Kenjis\CI4\AttributeRoutes\RoutePresenter;

#[RoutePresenter('presenter')]
class PresenterController extends ResourcePresenter
{
    ...
}
```

## Trouble Shooting

### No routes in the generated routes file

You must import the attribute classes in your controllers.

E.g.:
```php
use Kenjis\CI4\AttributeRoutes\Route;
...
    #[Route('news', methods: ['get'])]
    public function index()
```

### Can't be routed correctly, or 404 error occurs

Show your routes with the `php spark routes` command, and check the order of the routes.
The first matched route is the one that is executed.
The placeholders like `(.*)` or `([^/]+)` takes any characters or segment. So you have to move the routes like that to the bottom.

Set the priority of the routes with `options`:
```php
#[Route('news/(:segment)', methods: ['get'], options: ['priority' => 1])]
```
Zero is the default priority, and the higher the number specified in the `priority` option, the lower route priority in the processing queue.

## For Development

### Installation

    composer install

### Available Commands

    composer test              // Run unit test
    composer tests             // Test and quality checks
    composer cs-fix            // Fix the coding style
    composer sa                // Run static analysys tools
    composer run-script --list // List all commands
