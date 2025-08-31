<?php

namespace App\Http\Controllers;

use Sample\Workflow\Client\WorkflowClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class WorkflowController extends Controller
{
    public function getEvents(WorkflowClient $workflowClient, string $workflowId, string $runId): JsonResponse
    {
        return response()->json([
            'events' => $workflowClient->getWorkflowEvents($workflowId, $runId),
        ]);
    }
}
