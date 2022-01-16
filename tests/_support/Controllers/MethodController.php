<?php

declare(strict_types=1);

namespace Tests\Support\Controllers;

use CodeIgniter\Controller;
use Kenjis\CI4\AttributeRoutes\Route;

class MethodController extends Controller
{
    #[Route('method/a', methods: ['get'])]
    public function getA(): void
    {
    }

    #[Route('method/b', methods: ['get'], options: [])]
    public function getB(): void
    {
    }
}
