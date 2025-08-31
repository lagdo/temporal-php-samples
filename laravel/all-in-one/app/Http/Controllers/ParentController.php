<?php

namespace App\Http\Controllers;

use Sample\Workflow\Service\Workflow\Parent\GreetingParentWorkflowFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ParentController extends Controller
{
    public function startWorkflow(Request $request): JsonResponse
    {
        $jsonArguments = $request->getPayload()->all();
        $workflowArguments = $jsonArguments["args"] ?? [];
        // TODO: validate the input data here

        $workflowExecution = GreetingParentWorkflowFacade::startWorkflow(...$workflowArguments);

        return response()->json([
            'workflow' => $workflowExecution->getID(),
            'run' => $workflowExecution->getRunID(),
        ]);
    }
}
