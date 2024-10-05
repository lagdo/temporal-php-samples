<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class IndexController extends AbstractController
{
    #[Route(
        '/api/test',
        name: '/api_test',
        methods: [Request::METHOD_GET]
    )]
    public function test(): JsonResponse
    {
        return $this->json(['workflow' => 0, 'run' => 0]);
    }
}
