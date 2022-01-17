<?php

declare(strict_types=1);

namespace Tests\Support\Controllers;

use CodeIgniter\Controller;
use Kenjis\CI4\AttributeRoutes\RouteResource;

#[RouteResource('photos', options: ['websafe' => 1])]
class ResourceController extends Controller
{
    public function new(): void
    {
    }

    public function create(): void
    {
    }

    public function index(): void
    {
    }

    public function show(string $id): void
    {
    }
}
