<?php

declare(strict_types=1);

namespace Tests\Support\Controllers;

use CodeIgniter\Controller;
use Kenjis\CI4\AttributeRoutes\Route;
use Kenjis\CI4\AttributeRoutes\RouteGroup;

#[RouteGroup('group', options: ['filter' => 'auth'])]
class GroupController extends Controller
{
    #[Route('a', methods: ['get'])]
    public function getA(): void
    {
    }

    #[Route('a', methods: ['post'])]
    public function postA(): void
    {
    }

    public function noAttribute(): void
    {
    }
}
