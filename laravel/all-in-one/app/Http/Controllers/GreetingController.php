<?php

namespace App\Http\Controllers;

use Sample\Workflow\Service\Workflow\SimpleActivity\GreetingWorkflowFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GreetingController extends Controller
{
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = GreetingWorkflowFacade::startWorkflow(...$workflowArguments);

        return response()->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }
}
