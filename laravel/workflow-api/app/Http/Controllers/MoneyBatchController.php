<?php

namespace App\Http\Controllers;

use Sample\Workflow\Service\Workflow\MoneyBatch\MoneyBatchWorkflowFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MoneyBatchController extends Controller
{
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = MoneyBatchWorkflowFacade::startWorkflow(...$workflowArguments);

        return response()->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }

    public function getStatus(string $workflowId): JsonResponse
    {
        $workflow = MoneyBatchWorkflowFacade::getRunningWorkflow($workflowId);

        return response()->json([
            'count' => $workflow->getCount(),
            'balance' => $workflow->getBalance(),
        ]);
    }

    public function withdraw(Request $request, string $workflowId): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflow = MoneyBatchWorkflowFacade::getRunningWorkflow($workflowId);
        $workflow->withdraw(...$workflowArguments);

        return response()->json(['success'=> true]);
    }
}
