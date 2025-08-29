<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route(
        '/phpinfo',
        name: '/phpinfo',
        methods: [Request::METHOD_GET]
    )]
    public function phpinfo(): Response
    {
        return new Response('<html><body>'.phpinfo().'</body></html>');
    }
}
