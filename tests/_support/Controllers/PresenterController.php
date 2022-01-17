<?php

declare(strict_types=1);

namespace Tests\Support\Controllers;

use CodeIgniter\Controller;
use Kenjis\CI4\AttributeRoutes\RoutePresenter;

#[RoutePresenter('presenter')]
class PresenterController extends Controller
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

    public function remove(string $id): void
    {
    }

    public function delete(string $id): void
    {
    }
}
