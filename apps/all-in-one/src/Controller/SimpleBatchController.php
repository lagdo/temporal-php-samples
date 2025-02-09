<?php

namespace App\Controller;

use App\Workflow\Service\Workflow\SimpleBatch\SimpleBatchWorkflowFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

use function array_filter;
use function count;

#[AsController]
#[Route('/api/simple/batch', name: 'api_simple_batch_')]
class SimpleBatchController extends AbstractController
{
    #[Route(
        '/workflows',
        name: 'start_workflow',
        methods: [Request::METHOD_POST],
    )]
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = SimpleBatchWorkflowFacade::startWorkflow(...$workflowArguments);

        return $this->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }

    #[Route(
        '/workflows/{workflowId}/_status',
        name: 'get_workflow_status',
        methods: [Request::METHOD_GET]
    )]
    public function getStatus(string $workflowId): JsonResponse
    {
        $workflow = SimpleBatchWorkflowFacade::getRunningWorkflow($workflowId);
        $results = $workflow->getAvailableResults();
        $pending = $workflow->getPendingTasks();
        $failedCount = count(array_filter($results, fn(array $result) => !$result['success']));

        return $this->json([
            'count' => [
                'pending' => count($pending),
                'results' => count($results),
                'succeeded' => count($results) - $failedCount,
                'failed' => $failedCount,
            ],
            'tasks' => [
                'pending' => $pending,
                'results' => $results,
            ],
        ]);
    }
}
