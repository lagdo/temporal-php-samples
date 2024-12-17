<?php

namespace App\Controller;

use App\Workflow\Client\WorkflowClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api', name: 'api_')]
class WorkflowController extends AbstractController
{
    #[Route(
        '/workflows/{workflowId}/runs/{runId}/events',
        name: 'get_workflow_events',
        methods: [Request::METHOD_GET]
    )]
    public function getEvents(WorkflowClient $workflowClient, string $workflowId, string $runId): JsonResponse
    {
        return $this->json([
            'events' => $workflowClient->getWorkflowEvents($workflowId, $runId),
        ]);
    }
}
