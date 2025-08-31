<?php

namespace App\Http\Controllers;

use Sample\Workflow\Service\Workflow\SimpleBatch\SimpleBatchWorkflowFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use function array_filter;
use function count;

class SimpleBatchController extends Controller
{
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = SimpleBatchWorkflowFacade::startWorkflow(...$workflowArguments);

        return response()->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }

    public function getStatus(string $workflowId): JsonResponse
    {
        $workflow = SimpleBatchWorkflowFacade::getRunningWorkflow($workflowId);
        $results = $workflow->getAvailableResults();
        $pending = $workflow->getPendingTasks();
        $failedCount = count(array_filter($results, fn(array $result) => !$result['success']));

        return response()->json([
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
